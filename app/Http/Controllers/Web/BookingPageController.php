<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Providers\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BookingPageController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display homepage with featured fields
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $fields = Field::active()->latest()->take(4)->get();
        return view('pages.home', compact('fields'));
    }

    /**
     * Display all fields
     *
     * @return \Illuminate\View\View
     */
    public function fields()
    {
        $fields = Field::active()->latest()->get();
        return view('pages.fields', compact('fields'));
    }

    /**
     * Display field details
     *
     * @param Field $field
     * @return \Illuminate\View\View
     */
    public function fieldDetail(Field $field)
    {
        return view('pages.field-detail', compact('field'));
    }

    /**
     * Display booking form for a field
     *
     * @param Field $field
     * @return \Illuminate\View\View
     */
    public function bookingForm(Field $field)
    {
        $today = Carbon::today();
        $weeklyDates = [];

        // Generate dates for the next 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $weeklyDates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'day_name' => $date->format('l'),
                'formatted_date' => $date->format('d M Y')
            ];
        }

        // Get slots for today
        $slots = $this->bookingService->getAvailableSlots($field->id, $today->format('Y-m-d'));

        return view('pages.booking-form', compact('field', 'weeklyDates', 'slots'));
    }

    /**
     * Process booking submission
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'field_id' => 'required|exists:fields,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|date_format:Y-m-d',
            'selected_slots' => 'required|array|min:1',
            'payment_method' => 'required|in:online,cash',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Process selected slots
        $selectedSlots = $request->selected_slots;
        sort($selectedSlots);

        if (empty($selectedSlots)) {
            return redirect()->back()->with('error', 'Please select at least one time slot.')->withInput();
        }

        // Calculate start and end times
        $startTime = $selectedSlots[0];
        $endTime = Carbon::parse($selectedSlots[count($selectedSlots) - 1])->addHour()->format('H:i:s');

        // Check availability
        $isAvailable = $this->bookingService->areSlotsAvailable(
            $request->field_id,
            $request->booking_date,
            $startTime,
            $endTime
        );

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Selected slots are no longer available. Please choose different time slots.')->withInput();
        }

        // Create booking
        $bookingData = $request->all();
        $bookingData['start_time'] = $startTime;
        $bookingData['end_time'] = $endTime;

        $booking = $this->bookingService->createBooking($bookingData);

        if (!$booking) {
            return redirect()->back()->with('error', 'Failed to create booking. Please try again.')->withInput();
        }

        // Handle different payment methods
        if ($request->payment_method === 'online' && $booking->snap_token) {
            // Store booking ID in session for after payment
            Session::put('booking_id', $booking->id);

            // Redirect to payment page
            return view('pages.payment', [
                'booking' => $booking,
                'snapToken' => $booking->snap_token,
                'clientKey' => config('midtrans.client_key')
            ]);
        } else {
            // For cash payment, redirect to success page
            return redirect()->route('booking.success', $booking);
        }
    }

    /**
     * Display booking success page
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function bookingSuccess(Booking $booking)
    {
        $booking->load(['field', 'slots']);
        return view('pages.booking-success', compact('booking'));
    }

    /**
     * Handle payment finish callback from Midtrans
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finishPayment(Request $request)
    {
        $bookingId = Session::get('booking_id');

        if (!$bookingId) {
            return redirect()->route('home')->with('error', 'Booking not found.');
        }

        $booking = Booking::findOrFail($bookingId);

        // Clear session
        Session::forget('booking_id');

        return redirect()->route('booking.success', $booking);
    }

    /**
     * Handle payment unfinish callback from Midtrans
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfinishPayment(Request $request)
    {
        $bookingId = Session::get('booking_id');

        if (!$bookingId) {
            return redirect()->route('home')->with('error', 'Booking not found.');
        }

        $booking = Booking::findOrFail($bookingId);

        // Clear session
        Session::forget('booking_id');

        return redirect()->route('booking.success', $booking)
            ->with('warning', 'Your payment is still in process. You will receive a confirmation once the payment is completed.');
    }

    /**
     * Handle payment error callback from Midtrans
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function errorPayment(Request $request)
    {
        $bookingId = Session::get('booking_id');

        if (!$bookingId) {
            return redirect()->route('home')->with('error', 'Booking not found.');
        }

        $booking = Booking::findOrFail($bookingId);

        // Update booking status to failed
        $booking->status = 'failed';
        $booking->save();

        // Clear session
        Session::forget('booking_id');

        return redirect()->route('booking.form', $booking->field)
            ->with('error', 'Payment failed. Please try booking again.');
    }
}

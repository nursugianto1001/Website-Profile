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
use Illuminate\Support\Facades\DB;

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
     * Display booking form with multi-field selection capability
     *
     * @return \Illuminate\View\View
     */
    public function bookingForm()
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

        // Get slots based on opening and closing hours
        $openingHour = 8; // Default 8 AM
        $closingHour = 22; // Default 10 PM

        $slots = [];
        for ($hour = $openingHour; $hour < $closingHour; $hour++) {
            $slotTime = sprintf('%02d:00:00', $hour);
            $slots[] = [
                'time' => $slotTime,
                'formatted_time' => Carbon::parse($slotTime)->format('g:i A')
            ];
        }

        // Get availability for all fields
        $fieldAvailability = [];
        $fields = Field::where('is_active', true)->take(6)->get();

        foreach ($fields as $field) {
            $fieldId = $field->id;
            $fieldAvailability[$fieldId] = [];

            $bookedSlots = DB::table('booking_slots')
                ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                ->where('bookings.field_id', $fieldId)
                ->where('bookings.booking_date', $today->format('Y-m-d'))
                ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                ->pluck('booking_slots.slot_time')
                ->toArray();

            foreach ($slots as $slot) {
                $fieldAvailability[$fieldId][$slot['time']] = !in_array($slot['time'], $bookedSlots);
            }
        }

        return view('pages.booking-form', compact('weeklyDates', 'slots', 'fieldAvailability'));
    }

    /**
     * Get available slots for a specific date and all fields
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $fields = Field::where('is_active', true)->take(6)->get();

        $fieldAvailability = [];

        foreach ($fields as $field) {
            $fieldId = $field->id;
            $fieldAvailability[$fieldId] = [];

            $bookedSlots = DB::table('booking_slots')
                ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                ->where('bookings.field_id', $fieldId)
                ->where('bookings.booking_date', $date)
                ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                ->pluck('booking_slots.slot_time')
                ->toArray();

            $openingHour = $field->opening_hour ?? 8;
            $closingHour = $field->closing_hour ?? 22;

            for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                $slotTime = sprintf('%02d:00:00', $hour);
                $fieldAvailability[$fieldId][$slotTime] = !in_array($slotTime, $bookedSlots);
            }
        }

        return response()->json([
            'success' => true,
            'fieldAvailability' => $fieldAvailability
        ]);
    }

    /**
     * Process booking submission for multiple fields
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|date_format:Y-m-d',
            'selected_fields' => 'required|array|min:1',
            'selected_slots' => 'required|array|min:1',
            'payment_method' => 'required|in:online,cash',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bookingDate = $request->booking_date;
        $selectedFields = $request->selected_fields;
        $selectedSlots = $request->selected_slots;

        $bookings = [];
        $totalPrice = 0;

        DB::beginTransaction();
        try {
            // Create a booking for each field
            foreach ($selectedFields as $fieldId) {
                if (!isset($selectedSlots[$fieldId]) || empty($selectedSlots[$fieldId])) {
                    continue;
                }

                // Sort time slots
                $timeSlots = $selectedSlots[$fieldId];
                sort($timeSlots);

                if (empty($timeSlots)) {
                    continue;
                }

                // Calculate start and end times
                $startTime = $timeSlots[0];
                $endTime = Carbon::parse($timeSlots[count($timeSlots) - 1])->addHour()->format('H:i:s');

                // Check availability
                $isAvailable = $this->bookingService->areSlotsAvailable(
                    $fieldId,
                    $bookingDate,
                    $startTime,
                    $endTime
                );

                if (!$isAvailable) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Field {$fieldId} is no longer available for the selected time slots. Please choose different time slots.")->withInput();
                }

                // Create booking
                $bookingData = [
                    'field_id' => $fieldId,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'payment_method' => $request->payment_method
                ];

                $booking = $this->bookingService->createBooking($bookingData);

                if (!$booking) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Failed to create booking for Field {$fieldId}. Please try again.")->withInput();
                }

                $bookings[] = $booking;
                $totalPrice += $booking->total_price;
            }

            DB::commit();

            // Handle payment for all bookings collectively
            if ($request->payment_method === 'online') {
                // For online payment, we'll need to create a combined payment
                // In a real app, you'd want to create a transaction that references all bookings
                $mainBooking = $bookings[0]; // Use the first booking for payment

                // Store all booking IDs in session
                Session::put('booking_ids', array_map(function ($booking) {
                    return $booking->id;
                }, $bookings));

                // Redirect to payment page
                return view('pages.payment', [
                    'booking' => $mainBooking,
                    'totalBookings' => count($bookings),
                    'totalPrice' => $totalPrice,
                    'snapToken' => $mainBooking->snap_token,
                    'clientKey' => config('midtrans.client_key')
                ]);
            } else {
                // For cash payment, redirect to success page with the first booking
                return redirect()->route('booking.success', $bookings[0])
                    ->with('multipleBookings', true)
                    ->with('totalBookings', count($bookings));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
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

        $multipleBookings = Session::get('multipleBookings', false);
        $totalBookings = Session::get('totalBookings', 1);

        return view('pages.booking-success', compact('booking', 'multipleBookings', 'totalBookings'));
    }

    /**
     * Handle payment finish callback from Midtrans
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finishPayment(Request $request)
    {
        $bookingIds = Session::get('booking_ids', []);

        if (empty($bookingIds)) {
            return redirect()->route('home')->with('error', 'Booking not found.');
        }

        $booking = Booking::findOrFail($bookingIds[0]);

        // Clear session
        Session::forget('booking_ids');

        return redirect()->route('booking.success', $booking)
            ->with('multipleBookings', count($bookingIds) > 1)
            ->with('totalBookings', count($bookingIds));
    }

    /**
     * Handle payment unfinish callback from Midtrans
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfinishPayment(Request $request)
    {
        $bookingIds = Session::get('booking_ids', []);

        if (empty($bookingIds)) {
            return redirect()->route('home')->with('error', 'Booking not found.');
        }

        $booking = Booking::findOrFail($bookingIds[0]);

        // Clear session
        Session::forget('booking_ids');

        return redirect()->route('booking.success', $booking)
            ->with('multipleBookings', count($bookingIds) > 1)
            ->with('totalBookings', count($bookingIds))
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
        $bookingIds = Session::get('booking_ids', []);

        if (empty($bookingIds)) {
            return redirect()->route('home')->with('error', 'Booking not found.');
        }

        // Update all bookings to failed status
        foreach ($bookingIds as $id) {
            $booking = Booking::find($id);
            if ($booking) {
                $booking->status = 'failed';
                $booking->save();
            }
        }

        // Clear session
        Session::forget('booking_ids');

        return redirect()->route('booking.form')
            ->with('error', 'Payment failed. Please try booking again.');
    }
}

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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

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

        return view('pages.booking-form', compact('weeklyDates', 'slots', 'fieldAvailability', 'fields'));
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
                $mainBooking = $bookings[0]; // Use the first booking for payment

                // Store all booking IDs in session
                Session::put('booking_ids', array_map(function ($booking) {
                    return $booking->id;
                }, $bookings));

                // Generate signed URL for payment page
                $paymentUrl = URL::signedRoute('booking.payment', ['booking' => $mainBooking->id]);

                return redirect()->to($paymentUrl);
            } else {
                // Generate signed URL for success page
                $successUrl = URL::signedRoute('booking.success', ['booking' => $bookings[0]->id]);

                return redirect()->to($successUrl)
                    ->with('multipleBookings', true)
                    ->with('totalBookings', count($bookings));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display payment page
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function payment(Request $request, Booking $booking)
    {
        // Signature validation is handled by the 'signed' middleware

        $totalBookings = Session::get('totalBookings', 1);
        $totalPrice = $booking->total_price;

        return view('pages.payment', [
            'booking' => $booking,
            'totalBookings' => $totalBookings,
            'totalPrice' => $totalPrice,
            'snapToken' => $booking->snap_token,
            'clientKey' => config('midtrans.client_key')
        ]);
    }

    /**
     * Display booking success page
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function bookingSuccess(Request $request, Booking $booking)
    {
        // Signature validation is handled by the 'signed' middleware

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
        $orderId = $request->input('order_id'); // Get order_id from Midtrans callback

        if (empty($bookingIds)) {
            return redirect()->route('home')->with('error', 'Booking not found.');
        }

        $booking = Booking::with(['field', 'slots'])->findOrFail($bookingIds[0]);

        if (empty($booking->booking_code) && !empty($orderId)) {
            $booking->booking_code = $orderId;
            Log::info('Setting booking code: ' . $orderId);
        }

        // Update payment status
        if ($booking->payment_status == 'pending') {
            $booking->payment_status = 'settlement';
            $booking->status = 'confirmed';
            $saved = $booking->save();
            Log::info('Booking saved: ' . ($saved ? 'Yes' : 'No'));

            if (!$saved) {
                Log::error('Failed to save booking: ' . $booking->id);
            }
        }

        // Clear session
        Session::forget('booking_ids');

        // Generate signed URL for success page
        $successUrl = URL::signedRoute('booking.success', ['booking' => $booking->id]);

        return redirect()->to($successUrl)
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

        // Generate signed URL for success page
        $successUrl = URL::signedRoute('booking.success', ['booking' => $booking->id]);

        return redirect()->to($successUrl)
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

    /**
     * Handle payment notification from Midtrans
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handlePaymentNotification(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        // Log notification
        Log::info('Midtrans Notification: ', $payload);

        // Find booking by order_id
        $booking = Booking::where('booking_code', $orderId)->first();

        if (!$booking) {
            // If booking not found by booking_code, try to find by ID
            $bookingId = explode('-', $orderId)[0] ?? null;
            $booking = Booking::find($bookingId);

            if ($booking && empty($booking->booking_code)) {
                $booking->booking_code = $orderId;
            }
        }

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Update status pembayaran berdasarkan status transaksi
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            // Pembayaran berhasil
            $booking->payment_status = 'settlement';
            $booking->status = 'confirmed';
        } elseif ($transactionStatus == 'pending') {
            // Pembayaran pending
            $booking->payment_status = 'pending';
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Pembayaran gagal
            $booking->payment_status = 'failed';
            $booking->status = 'cancelled';
        }

        $booking->save();

        return response()->json(['message' => 'Notification processed successfully']);
    }
}

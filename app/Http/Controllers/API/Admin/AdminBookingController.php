<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Providers\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminBookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Get all bookings with pagination
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Booking::with(['field', 'transaction']);

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by field
        if ($request->has('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('booking_date', [$request->start_date, $request->end_date]);
        }

        // Search by customer info
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    /**
     * Show specific booking details
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $booking->load(['field', 'slots', 'transaction']);

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    /**
     * Store a new booking (admin can create booking directly)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'field_id' => 'required|exists:fields,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'payment_method' => 'required|in:online,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check slot availability
        $isAvailable = $this->bookingService->areSlotsAvailable(
            $request->field_id,
            $request->booking_date,
            $request->start_time,
            $request->end_time
        );

        if (!$isAvailable) {
            return response()->json([
                'success' => false,
                'message' => 'Selected slots are no longer available'
            ], 400);
        }

        // Create booking
        $booking = $this->bookingService->createBooking($request->all());

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking'
            ], 500);
        }

        // Load relationships
        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking,
            'whatsapp_invoice_url' => $booking->payment_instruction ?? null
        ], 201);
    }

    /**
     * Update an existing booking
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_status' => 'nullable|in:pending,settlement,expired,cancel',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking->update($request->only([
            'customer_name',
            'customer_email',
            'customer_phone',
            'payment_status'
        ]));

        // If payment status is updated to cancelled, update slots too
        if ($request->has('payment_status') && $request->payment_status === 'cancel') {
            $booking->slots()->update(['status' => 'cancelled']);

            // Also update transaction if exists
            if ($booking->transaction) {
                $booking->transaction->update(['transaction_status' => 'cancel']);
            }
        }

        $booking->load(['field', 'slots', 'transaction']);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => $booking
        ]);
    }

    /**
     * Cancel a booking
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function cancel(Booking $booking)
    {
        $result = $this->bookingService->cancelBooking($booking);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking'
            ], 500);
        }

        $booking->load(['field', 'slots', 'transaction']);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully',
            'data' => $booking
        ]);
    }

    /**
     * Delete a booking (soft delete can be implemented if needed)
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        // If booking has payments, don't allow deletion
        if ($booking->transaction && in_array($booking->payment_status, ['settlement'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a booking with confirmed payment'
            ], 400);
        }

        // Delete related slots first
        $booking->slots()->delete();

        // Delete related transaction if exists
        if ($booking->transaction) {
            $booking->transaction->delete();
        }

        // Delete the booking
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }
}

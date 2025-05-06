<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Providers\BookingService;
use App\Providers\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    protected $bookingService;
    protected $midtransService;

    public function __construct(BookingService $bookingService, MidtransService $midtransService)
    {
        $this->bookingService = $bookingService;
        $this->midtransService = $midtransService;
    }

    /**
     * Check slot buat booking
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $isAvailable = $this->bookingService->areSlotsAvailable(
            $request->field_id,
            $request->booking_date,
            $request->start_time,
            $request->end_time
        );

        return response()->json([
            'success' => true,
            'data' => [
                'is_available' => $isAvailable
            ]
        ]);
    }

    /**
     * Store booking yg baru
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

        // Check slot jika ada
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
        $booking->load('field');

        $response = [
            'success' => true,
            'message' => $request->payment_method === 'cash'
                ? 'Booking created successfully with cash payment'
                : 'Booking created successfully, proceed to payment',
            'data' => $booking
        ];

        // Add payment info for online payment
        if ($request->payment_method === 'online') {
            $response['payment'] = [
                'snap_token' => $booking->snap_token,
                'client_key' => $this->midtransService->getClientKey()
            ];
        }

        return response()->json($response, 201);
    }

    /**
     * Get booking details
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
}

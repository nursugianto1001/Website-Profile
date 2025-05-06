<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Field;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingService
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Check if slots are available for booking
     */
    public function areSlotsAvailable($fieldId, $date, $startTime, $endTime)
    {
        // Check if there are any overlapping bookings
        return !BookingSlot::where('field_id', $fieldId)
            ->where('booking_date', $date)
            ->where('status', 'booked')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();
    }

    /**
     * Create a new booking
     */
    public function createBooking($data)
    {
        // Generate booking code
        $bookingCode = 'BK-' . strtoupper(Str::random(8));

        // Get field details
        $field = Field::findOrFail($data['field_id']);

        // Create booking
        $booking = Booking::create([
            'field_id' => $data['field_id'],
            'booking_code' => $bookingCode,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'booking_date' => $data['booking_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'duration_hours' => $data['duration_hours'],
            'total_price' => $data['total_price'],
            'payment_method' => $data['payment_method'],
            'payment_status' => $data['payment_status'],
        ]);

        if (!$booking) {
            return null;
        }

        // Create booking slots
        $this->createBookingSlots($booking);

        // For online payment, create Midtrans transaction
        if ($data['payment_method'] === 'online') {
            $this->createMidtransTransaction($booking);
        }

        return $booking;
    }

    /**
     * Create booking slots for a booking
     */
    private function createBookingSlots($booking)
    {
        $startTime = Carbon::parse($booking->start_time);
        $endTime = Carbon::parse($booking->end_time);
        $currentTime = $startTime->copy();

        while ($currentTime < $endTime) {
            BookingSlot::create([
                'booking_id' => $booking->id,
                'field_id' => $booking->field_id,
                'booking_date' => $booking->booking_date,
                'start_time' => $currentTime->format('H:i:s'),
                'end_time' => $currentTime->copy()->addHour()->format('H:i:s'),
                'status' => 'booked',
            ]);

            $currentTime->addHour();
        }
    }

    /**
     * Create Midtrans transaction for a booking
     */
    private function createMidtransTransaction($booking)
    {
        // Load field relationship
        $booking->load('field');

        // Prepare transaction data
        $transactionData = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) $booking->total_price,
            ],
            'item_details' => [
                [
                    'id' => $booking->field_id,
                    'price' => (int) $booking->field->price_per_hour,
                    'quantity' => $booking->duration_hours,
                    'name' => $booking->field->name,
                ]
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
            ],
        ];

        // Create Midtrans transaction
        $snapToken = $this->midtransService->createTransaction($transactionData);

        if ($snapToken) {
            // Update booking with snap token
            $booking->update(['snap_token' => $snapToken]);

            // Create transaction record
            Transaction::create([
                'booking_id' => $booking->id,
                'transaction_id' => $booking->booking_code,
                'amount' => $booking->total_price,
                'status' => 'pending',
                'payment_type' => 'midtrans',
                'snap_token' => $snapToken,
            ]);
        }
    }

    /**
     * Create a transaction for multiple bookings
     */
    public function createMultipleBookingTransaction($bookings, $totalAmount)
    {
        // Generate transaction ID
        $transactionId = 'TX-' . strtoupper(Str::random(8));

        // Get first booking for customer details
        $firstBooking = $bookings[0];

        // Prepare transaction data
        $transactionData = [
            'transaction_details' => [
                'order_id' => $transactionId,
                'gross_amount' => (int) $totalAmount,
            ],
            'item_details' => [],
            'customer_details' => [
                'first_name' => $firstBooking->customer_name,
                'email' => $firstBooking->customer_email,
                'phone' => $firstBooking->customer_phone,
            ],
        ];

        // Add items for each booking
        foreach ($bookings as $booking) {
            $booking->load('field');

            $transactionData['item_details'][] = [
                'id' => $booking->field_id,
                'price' => (int) $booking->field->price_per_hour,
                'quantity' => $booking->duration_hours,
                'name' => $booking->field->name . ' (' . $booking->booking_date . ')',
            ];
        }

        // Create Midtrans transaction
        $snapToken = $this->midtransService->createTransaction($transactionData);

        if ($snapToken) {
            // Create transaction record
            $transaction = Transaction::create([
                'transaction_id' => $transactionId,
                'amount' => $totalAmount,
                'status' => 'pending',
                'payment_type' => 'midtrans',
                'snap_token' => $snapToken,
            ]);

            // Link bookings to this transaction
            foreach ($bookings as $booking) {
                $booking->update([
                    'transaction_id' => $transaction->id,
                    'snap_token' => $snapToken
                ]);
            }

            return $transaction;
        }

        return null;
    }
}

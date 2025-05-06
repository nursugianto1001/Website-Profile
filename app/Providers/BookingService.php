<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Field;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BookingService
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Check if slots are available for booking
     *
     * @param int $fieldId
     * @param string $bookingDate
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    public function areSlotsAvailable($fieldId, $bookingDate, $startTime, $endTime)
    {
        // Convert times to Carbon instances for easier comparison
        $bookingDate = Carbon::parse($bookingDate)->format('Y-m-d');
        $startDateTime = Carbon::parse("$bookingDate $startTime");
        $endDateTime = Carbon::parse("$bookingDate $endTime");

        // Get all booked slots for the field on the given date
        $bookedSlots = BookingSlot::whereHas('booking', function ($query) use ($fieldId, $bookingDate) {
            $query->where('field_id', $fieldId)
                ->where('booking_date', $bookingDate)
                ->whereNotIn('payment_status', ['expired', 'cancel']);
        })->get();

        // Calculate booking slots (1 hour per slot)
        $currentSlot = $startDateTime->copy();
        $requestedSlots = [];

        while ($currentSlot < $endDateTime) {
            $slotEnd = $currentSlot->copy()->addHour();
            $requestedSlots[] = $currentSlot->format('H:i:s');
            $currentSlot = $slotEnd;
        }

        // Check if any requested slot overlaps with existing booked slots
        foreach ($requestedSlots as $slot) {
            $slotStart = Carbon::parse("$bookingDate $slot");
            $slotEnd = $slotStart->copy()->addHour();

            foreach ($bookedSlots as $bookedSlot) {
                $bookedSlotTime = Carbon::parse("$bookingDate $bookedSlot->slot_time");

                // If there's an overlap, slot is not available
                if ($slotStart <= $bookedSlotTime && $bookedSlotTime < $slotEnd) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get all available slots for a field on a specific date
     *
     * @param int $fieldId
     * @param string $date
     * @return array
     */
    public function getAvailableSlots($fieldId, $date)
    {
        $field = Field::findOrFail($fieldId);
        $date = Carbon::parse($date)->format('Y-m-d');

        // Get opening and closing hours
        $openingHour = $field->opening_hour ?? 8; // Default 8 AM
        $closingHour = $field->closing_hour ?? 22; // Default 10 PM

        // Get all booked slots for the field on the given date
        $bookedSlots = BookingSlot::whereHas('booking', function ($query) use ($fieldId, $date) {
            $query->where('field_id', $fieldId)
                ->where('booking_date', $date)
                ->whereNotIn('payment_status', ['expired', 'cancel']);
        })->pluck('slot_time')->toArray();

        // Generate all possible slots
        $allSlots = [];
        for ($hour = $openingHour; $hour < $closingHour; $hour++) {
            $slotTime = sprintf('%02d:00:00', $hour);
            $allSlots[] = [
                'time' => $slotTime,
                'formatted_time' => Carbon::parse($slotTime)->format('g:i A'),
                'is_available' => !in_array($slotTime, $bookedSlots)
            ];
        }

        return $allSlots;
    }

    /**
     * Create a new booking
     *
     * @param array $data
     * @return Booking|null
     */
    public function createBooking($data)
    {
        try {
            DB::beginTransaction();

            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);
            $hours = $endTime->diffInHours($startTime);

            // Get field details
            $field = Field::findOrFail($data['field_id']);
            $totalPrice = $field->price_per_hour * $hours;

            // Create booking
            $booking = Booking::create([
                'field_id' => $data['field_id'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'booking_date' => $data['booking_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'total_price' => $totalPrice,
                'payment_status' => 'pending',
            ]);

            // Create booking slots (1 slot per hour)
            $currentSlot = $startTime->copy();
            while ($currentSlot < $endTime) {
                BookingSlot::create([
                    'booking_id' => $booking->id,
                    'slot_time' => $currentSlot->format('H:i:s'),
                    'status' => 'booked'
                ]);
                $currentSlot->addHour();
            }

            // Handle payment method
            if (isset($data['payment_method']) && $data['payment_method'] === 'online') {
                // Generate Midtrans payment
                $snapToken = $this->midtransService->createTransaction([
                    'booking_id' => $booking->id,
                    'customer_name' => $booking->customer_name,
                    'customer_email' => $booking->customer_email,
                    'customer_phone' => $booking->customer_phone,
                    'total_price' => $booking->total_price,
                    'items' => [
                        [
                            'id' => $field->id,
                            'name' => $field->name,
                            'price' => $field->price_per_hour,
                            'quantity' => $hours
                        ]
                    ]
                ]);

                if ($snapToken) {
                    $booking->update(['snap_token' => $snapToken]);

                    // Create transaction record
                    Transaction::create([
                        'booking_id' => $booking->id,
                        'order_id' => 'ORD-' . $booking->id . '-' . time(),
                        'transaction_status' => 'pending',
                        'gross_amount' => $booking->total_price,
                        'transaction_time' => now(),
                    ]);
                }
            } else if (isset($data['payment_method']) && $data['payment_method'] === 'cash') {
                // Create cash transaction
                Transaction::create([
                    'booking_id' => $booking->id,
                    'order_id' => 'CASH-' . $booking->id . '-' . time(),
                    'payment_type' => 'cash',
                    'transaction_status' => 'pending',
                    'gross_amount' => $booking->total_price,
                    'transaction_time' => now(),
                    'is_manual' => true,
                ]);
            }

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create booking: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cancel a booking
     *
     * @param Booking $booking
     * @return bool
     */
    public function cancelBooking(Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Update booking status
            $booking->update(['payment_status' => 'cancel']);

            // Update slots
            $booking->slots()->update(['status' => 'cancelled']);

            // Update transaction if exists
            if ($booking->transaction) {
                $booking->transaction->update(['transaction_status' => 'cancel']);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to cancel booking: ' . $e->getMessage());
            return false;
        }
    }
}

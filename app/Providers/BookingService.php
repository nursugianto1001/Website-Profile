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
        $bookedSlots = DB::table('booking_slots')
            ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
            ->where('bookings.field_id', $fieldId)
            ->where('bookings.booking_date', $bookingDate)
            ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
            ->select('booking_slots.slot_time')
            ->get();

        // Calculate booking slots (1 hour per slot)
        $currentSlot = $startDateTime->copy();
        $requestedSlots = [];

        while ($currentSlot < $endDateTime) {
            $requestedSlots[] = $currentSlot->format('H:i:s');
            $currentSlot->addHour();
        }

        // Check if any requested slot overlaps with existing booked slots
        foreach ($requestedSlots as $slot) {
            foreach ($bookedSlots as $bookedSlot) {
                if ($slot === $bookedSlot->slot_time) {
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
        $bookedSlots = DB::table('booking_slots')
            ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
            ->where('bookings.field_id', $fieldId)
            ->where('bookings.booking_date', $date)
            ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
            ->pluck('booking_slots.slot_time')
            ->toArray();

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
            $currentTransaction = DB::transactionLevel() > 0;
            if (!$currentTransaction) {
                DB::beginTransaction();
            }

            $bookingDate = Carbon::parse($data['booking_date'])->format('Y-m-d');
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);

            if ($startTime->format('Y-m-d') !== $endTime->format('Y-m-d')) {
                $endTime->setDate($startTime->year, $startTime->month, $startTime->day);
            }

            $hours = abs($endTime->diffInHours($startTime));
            $field = Field::findOrFail($data['field_id']);
            $totalPrice = abs($field->price_per_hour * $hours);

            $booking = Booking::create([
                'field_id' => $data['field_id'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'booking_date' => $bookingDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $hours,
                'total_price' => $totalPrice,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'status' => 'booked',
                'booking_code' => null,
            ]);

            // Buat slot booking
            $currentSlot = $startTime->copy();
            while ($currentSlot < $endTime) {
                BookingSlot::create([
                    'booking_id' => $booking->id,
                    'field_id' => $data['field_id'],
                    'booking_date' => $bookingDate,
                    'slot_time' => $currentSlot->format('H:i:s'),
                    'start_time' => $currentSlot->format('Y-m-d H:i:s'),
                    'end_time' => $currentSlot->copy()->addHour()->format('Y-m-d H:i:s'),
                    'status' => 'booked'
                ]);
                $currentSlot->addHour();
            }

            // Pembayaran cash: kirim invoice WhatsApp
            if ($data['payment_method'] === 'cash') {
                try {
                    $adminPhone = env('ADMIN_WHATSAPP_NUMBER', '6281254767505');
                    $fieldName = $field->name ?: "Lapangan #$field->id";
                    $formattedDate = Carbon::parse($bookingDate)->format('d M Y');
                    $timeRange = $startTime->format('H:i') . '-' . $endTime->format('H:i');
                    $message = "📋 *INVOICE PEMESANAN CASH*\n\n"
                        . "Nama: {$booking->customer_name}\n"
                        . "Telp: {$booking->customer_phone}\n"
                        . "Tanggal: {$formattedDate}\n"
                        . "Waktu: {$timeRange}\n"
                        . "Lapangan: {$fieldName}\n"
                        . "Total: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n"
                        . "Segera konfirmasi slot!";
                    $waUrl = "https://api.whatsapp.com/send?phone={$adminPhone}&text=" . urlencode($message);
                    $booking->update(['payment_instruction' => $waUrl]);
                } catch (\Exception $e) {
                    Log::error('Gagal buat WhatsApp invoice: ' . $e->getMessage());
                }
            }

            if (!$currentTransaction) {
                DB::commit();
            }

            $booking->refresh();
            return $booking;
        } catch (\Exception $e) {
            if (!isset($currentTransaction) || !$currentTransaction) {
                DB::rollBack();
            }
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
            $booking->update([
                'payment_status' => 'cancel',
                'status' => 'cancelled'
            ]);

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

    /**
     * Update booking payment status
     *
     * @param Booking $booking
     * @param string $status
     * @return bool
     */
    public function updatePaymentStatus(Booking $booking, $status)
    {
        try {
            DB::beginTransaction();

            // Update booking status
            $bookingStatus = $status === 'settlement' ? 'paid' : $status;
            $booking->update([
                'payment_status' => $bookingStatus,
                'status' => $status === 'settlement' ? 'confirmed' : 'pending'
            ]);

            // Update transaction if exists
            if ($booking->transaction) {
                $booking->transaction->update(['transaction_status' => $status]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update payment status: ' . $e->getMessage());
            return false;
        }
    }
}

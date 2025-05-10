<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingService
{
    /**
     * Cek ketersediaan slot lapangan
     */
    public function areSlotsAvailable($fieldId, $bookingDate, $startTime, $endTime)
    {
        $bookingDate = Carbon::parse($bookingDate)->format('Y-m-d');
        $startDateTime = Carbon::parse("$bookingDate $startTime");
        $endDateTime = Carbon::parse("$bookingDate $endTime");

        $bookedSlots = DB::table('booking_slots')
            ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
            ->where('bookings.field_id', $fieldId)
            ->where('bookings.booking_date', $bookingDate)
            ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
            ->pluck('booking_slots.slot_time')
            ->toArray();

        $requestedSlots = [];
        $currentSlot = $startDateTime->copy();
        while ($currentSlot < $endDateTime) {
            $requestedSlots[] = $currentSlot->format('H:i:s');
            $currentSlot->addHour();
        }

        foreach ($requestedSlots as $slot) {
            if (in_array($slot, $bookedSlots)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Mendapatkan slot tersedia untuk lapangan pada tanggal tertentu
     */
    public function getAvailableSlots($fieldId, $date)
    {
        $field = Field::findOrFail($fieldId);
        $date = Carbon::parse($date)->format('Y-m-d');
        $openingHour = $field->opening_hour ?? 8;
        $closingHour = $field->closing_hour ?? 22;

        $bookedSlots = DB::table('booking_slots')
            ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
            ->where('bookings.field_id', $fieldId)
            ->where('bookings.booking_date', $date)
            ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
            ->pluck('booking_slots.slot_time')
            ->toArray();

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
     * Generate pesan invoice booking cash untuk WhatsApp admin (wa.me link)
     */
    public function generateCashInvoiceWhatsApp($data)
    {
        $adminPhone = env('ADMIN_WHATSAPP_NUMBER', '6281254767505');
        $invoice = "📋 *INVOICE PEMESANAN CASH*\n\n"
            . "Nama: {$data['customer_name']}\n"
            . "Telp: {$data['customer_phone']}\n"
            . "Tanggal: " . \Carbon\Carbon::parse($data['booking_date'])->format('d M Y') . "\n\n";
        $totalPrice = 0;

        foreach ($data['selected_fields'] as $fieldId) {
            $field = \App\Models\Field::find($fieldId);
            if (!$field) continue;
            $slots = $data['selected_slots'][$fieldId] ?? [];
            if (empty($slots)) continue;
            sort($slots);
            $start = \Carbon\Carbon::parse($slots[0])->format('H:i');
            $end = \Carbon\Carbon::parse(end($slots))->addHour()->format('H:i');
            $subtotal = $field->price_per_hour * count($slots);
            $totalPrice += $subtotal;
            $invoice .= "Lapangan: {$field->name}\n";
            $invoice .= "Waktu: {$start}-{$end}\n";
            $invoice .= "Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
        }
        $invoice .= "*Total: Rp " . number_format($totalPrice, 0, ',', '.') . "*\n";
        $waUrl = "https://wa.me/{$adminPhone}?text=" . urlencode($invoice);

        return [
            'wa_url' => $waUrl,
            'total_price' => $totalPrice,
            'invoice_text' => $invoice,
        ];
    }



    /**
     * Buat booking baru (hanya dipanggil setelah admin konfirmasi)
     */
    public function createBooking($data)
    {
        try {
            DB::beginTransaction();
            $bookingDate = Carbon::parse($data['booking_date'])->format('Y-m-d');
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);
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

            DB::commit();
            $booking->refresh();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create booking: ' . $e->getMessage());
            return null;
        }
    }
}

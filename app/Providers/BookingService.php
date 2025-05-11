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
     * Cek ketersediaan slot dengan validasi waktu real-time
     */
    public function areSlotsAvailable($fieldId, $bookingDate, $startTime, $endTime)
    {
        $bookingDate = Carbon::parse($bookingDate)->format('Y-m-d');
        $startDateTime = Carbon::parse("$bookingDate $startTime");
        $endDateTime = Carbon::parse("$bookingDate $endTime");
        $isToday = Carbon::parse($bookingDate)->isToday();

        // Validasi waktu tidak valid
        if ($startDateTime->gte($endDateTime)) {
            return false;
        }

        $bookedSlots = DB::table('booking_slots')
            ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
            ->where('bookings.field_id', $fieldId)
            ->where('bookings.booking_date', $bookingDate)
            ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
            ->pluck('booking_slots.slot_time')
            ->toArray();

        $requestedSlots = [];
        $currentSlot = $startDateTime->copy();

        // Generate slot per jam dengan validasi waktu
        while ($currentSlot < $endDateTime) {
            $slotTime = $currentSlot->format('H:i:s');

            // Validasi slot hari ini yang sudah lewat
            if ($isToday && $currentSlot->lt(now())) {
                return false;
            }

            // Validasi konflik dengan booking lain
            if (in_array($slotTime, $bookedSlots)) {
                return false;
            }

            $currentSlot->addHour();
        }

        return true;
    }

    /**
     * Mendapatkan slot tersedia dengan validasi real-time
     */
    public function getAvailableSlotsData($date)
    {
        $date = Carbon::parse($date, 'Asia/Jakarta')->format('Y-m-d');
        $fields = Field::where('is_active', true)->get();
        $result = [];
        $isToday = Carbon::parse($date, 'Asia/Jakarta')->isToday();
        $currentTime = now('Asia/Jakarta');

        foreach ($fields as $field) {
            $fieldId = $field->id;
            $openingHour = $field->opening_hour ?? 8;
            $closingHour = $field->closing_hour ?? 22;

            $bookedSlots = DB::table('booking_slots')
                ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                ->where('bookings.field_id', $fieldId)
                ->where('bookings.booking_date', $date)
                ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                ->pluck('booking_slots.slot_time')
                ->toArray();

            $slots = [];
            for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                $slotTime = sprintf('%02d:00:00', $hour);
                $slotStart = Carbon::createFromFormat('Y-m-d H:i:s', "$date $slotTime", 'Asia/Jakarta');
                $slotEnd = $slotStart->copy()->addHour();

                $isPast = $isToday && $slotEnd->lte($currentTime);
                $isBooked = in_array($slotTime, $bookedSlots, true);

                $slots[$slotTime] = [
                    'formatted_time' => $slotStart->format('H:i') . '-' . $slotEnd->format('H:i'),
                    'is_available' => !$isBooked && !$isPast
                ];
            }

            $result[$fieldId] = $slots;
        }

        return $result;
    }

    /**
     * Generate invoice WhatsApp untuk multi-lapangan
     */
    public function generateCashInvoiceWhatsapp(array $data): array
    {
        $adminPhone = env('ADMIN_WHATSAPP_NUMBER', '6281234567890');
        $invoice = "INVOICE PEMESANAN CASH\n\n"
            . "Nama: {$data['customer_name']}\n"
            . "Telp: {$data['customer_phone']}\n"
            . "Tanggal: " . Carbon::parse($data['booking_date'])->format('d M Y') . "\n\n";

        $totalPrice = 0;

        foreach ($data['selected_fields'] as $fieldId) {
            $field = Field::find($fieldId);
            if (!$field) continue;

            $slots = $data['selected_slots'][$fieldId] ?? [];
            if (empty($slots)) continue;

            sort($slots);
            $start = Carbon::parse($slots[0])->format('H:i');
            $end = Carbon::parse(end($slots))->addHour()->format('H:i');
            $duration = count($slots);
            $subtotal = $field->price_per_hour * $duration;
            $totalPrice += $subtotal;

            $invoice .= "Lapangan: {$field->name}\n"
                . "Waktu: {$start} - {$end}\n"
                . "Durasi: {$duration} jam\n"
                . "Harga: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
        }

        $invoice .= "------------------------\n"
            . "TOTAL: Rp " . number_format($totalPrice, 0, ',', '.') . "\n\n";

        return [
            'wa_url' => "https://wa.me/{$adminPhone}?text=" . urlencode($invoice),
            'total_price' => $totalPrice,
            'invoice_text' => $invoice
        ];
    }

    /**
     * Proses pembuatan booking dengan validasi lengkap
     */
    public function createBooking(array $data, bool $isConfirmed = false): ?Booking
    {
        try {
            DB::beginTransaction();

            $bookingDate = Carbon::parse($data['booking_date'])->format('Y-m-d');
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);
            $field = Field::findOrFail($data['field_id']);

            // Validasi waktu
            if ($startTime->gte($endTime)) {
                throw new \Exception("Waktu akhir harus setelah waktu awal");
            }

            // Perbaikan perhitungan durasi
            $duration = $startTime->diffInHours($endTime);
            // Atau gunakan parameter absolut
            // $duration = $endTime->diffInHours($startTime, false);

            $totalPrice = $field->price_per_hour * $duration;

            // Generate booking code hanya untuk yang sudah dikonfirmasi
            $bookingCode = $isConfirmed
                ? 'CASH-' . Carbon::now()->format('YmdHis') . '-' . strtoupper(uniqid())
                : null;

            $bookingData = [
                'field_id' => $field->id,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'] ?? null,
                'customer_phone' => $data['customer_phone'],
                'booking_date' => $bookingDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $duration,
                'total_price' => $totalPrice,
                'payment_method' => $data['payment_method'],
                'payment_status' => $isConfirmed ? 'settlement' : 'pending',
                'status' => $isConfirmed ? 'booked' : 'pending',
                'booking_code' => $bookingCode
            ];

            $booking = Booking::create($bookingData);

            // Hanya buat slot jika booking dikonfirmasi
            if ($isConfirmed) {
                $currentSlot = $startTime->copy();
                while ($currentSlot < $endTime) {
                    BookingSlot::create([
                        'booking_id' => $booking->id,
                        'field_id' => $field->id,
                        'booking_date' => $bookingDate,
                        'slot_time' => $currentSlot->format('H:i:s'),
                        'start_time' => $currentSlot->format('Y-m-d H:i:s'),
                        'end_time' => $currentSlot->addHour()->format('Y-m-d H:i:s'),
                        'status' => 'booked'
                    ]);
                }
            }

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking failed: ' . $e->getMessage());
            return null;
        }
    }


    /**
     * Membatalkan booking dengan menghapus data dari database
     * 
     * @param Booking $booking
     * @return bool
     */
    public function cancelBooking(Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Hapus slot booking terlebih dahulu
            BookingSlot::where('booking_id', $booking->id)->delete();

            // Hapus transaksi terkait jika ada
            if ($booking->transaction) {
                $booking->transaction->delete();
            }

            // Hapus booking dari database
            $result = $booking->delete();

            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membatalkan booking: ' . $e->getMessage());
            return false;
        }
    }
}

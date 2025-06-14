<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Field;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingService
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

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

            $duration = $startTime->diffInHours($endTime);
            $totalPrice = $field->price_per_hour * $duration;

            // Generate booking code hanya untuk yang sudah dikonfirmasi
            $bookingCode = $isConfirmed
                ? 'CASH-' . Carbon::now()->format('YmdHis') . '-' . strtoupper(uniqid())
                : 'BK-' . strtoupper(Str::random(8));

            $bookingData = [
                'field_id' => $field->id,
                'booking_code' => $bookingCode,
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
            ];

            $booking = Booking::create($bookingData);

            if (!$booking) {
                throw new \Exception('Failed to create booking');
            }

            Log::info('Booking created:', ['booking_id' => $booking->id]);

            // ✅ PERBAIKAN: Selalu buat transaction record
            $this->createTransactionRecord($booking);

            // Hanya buat slot jika booking dikonfirmasi
            if ($isConfirmed) {
                $this->createBookingSlots($booking);
            }

            // For online payment, create Midtrans transaction
            if ($data['payment_method'] === 'online') {
                $this->createMidtransTransaction($booking);
            }

            DB::commit();
            Log::info('Booking process completed:', ['booking_id' => $booking->id]);
            
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create transaction record for any booking
     */
    private function createTransactionRecord($booking)
    {
        $orderId = 'ORDER-' . $booking->id . '-' . time();
        
        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'payment_type' => $booking->payment_method === 'online' ? 'midtrans' : 'cash',
            'transaction_status' => $booking->payment_status === 'settlement' ? 'settlement' : 'pending',
            'gross_amount' => $booking->total_price,
            'payment_channel' => $booking->payment_method === 'online' ? 'Midtrans' : 'Manual',
            'transaction_time' => now(),
        ]);

        Log::info('Transaction record created:', [
            'transaction_id' => $transaction->id,
            'booking_id' => $booking->id,
            'order_id' => $orderId
        ]);

        return $transaction;
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
                'slot_time' => $currentTime->format('H:i:s'),
                'start_time' => $currentTime->format('Y-m-d H:i:s'),
                'end_time' => $currentTime->copy()->addHour()->format('Y-m-d H:i:s'),
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
        $booking->load('field');

        // ✅ PERBAIKAN: Siapkan data sesuai format MidtransService
        $transactionData = [
            'booking_id' => $booking->id,
            'customer_name' => $booking->customer_name,
            'customer_email' => $booking->customer_email,
            'customer_phone' => $booking->customer_phone,
            'total_price' => $booking->total_price,
            'items' => [
                [
                    'id' => $booking->field_id,
                    'name' => $booking->field->name,
                    'price' => $booking->field->price_per_hour,
                    'quantity' => $booking->duration_hours
                ]
            ]
        ];

        $midtransResponse = $this->midtransService->createTransaction($transactionData);

        if ($midtransResponse && isset($midtransResponse['token'])) {
            // Update booking dengan snap token
            $booking->update(['snap_token' => $midtransResponse['token']]);

            // ✅ PERBAIKAN: Update transaction yang sudah dibuat dengan order_id dari Midtrans
            $transaction = Transaction::where('booking_id', $booking->id)->first();
            if ($transaction) {
                $transaction->update([
                    'order_id' => $midtransResponse['order_id'],
                    'payment_type' => 'midtrans',
                ]);
            }

            Log::info('Midtrans transaction created successfully', [
                'booking_id' => $booking->id,
                'order_id' => $midtransResponse['order_id']
            ]);
        }
    }

    /**
     * Membatalkan booking dengan menghapus data dari database
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

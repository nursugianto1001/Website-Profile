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
     * Cek ketersediaan slot dengan validasi waktu real-time dan konflik booking
     */
    public function areSlotsAvailable($fieldId, $bookingDate, $startTime, $endTime, $excludeBookingId = null)
    {
        $bookingDate = Carbon::parse($bookingDate)->format('Y-m-d');
        $startDateTime = Carbon::parse("$bookingDate $startTime");
        $endDateTime = Carbon::parse("$bookingDate $endTime");
        $isToday = Carbon::parse($bookingDate)->isToday();

        // Validasi waktu tidak valid
        if ($startDateTime->gte($endDateTime)) {
            return false;
        }

        // VALIDASI KONFLIK DENGAN TABEL BOOKINGS
        $conflictBooking = Booking::where('field_id', $fieldId)
            ->where('booking_date', $bookingDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    // Booking baru dimulai di tengah booking yang ada
                    $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>', $startTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        // Booking baru berakhir di tengah booking yang ada
                        $q->where('start_time', '<', $endTime)
                            ->where('end_time', '>=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        // Booking baru mencakup booking yang ada
                        $q->where('start_time', '>=', $startTime)
                            ->where('end_time', '<=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        // Booking yang ada mencakup booking baru
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->whereNotIn('payment_status', ['expired', 'cancel', 'failed']);

        if ($excludeBookingId) {
            $conflictBooking->where('id', '!=', $excludeBookingId);
        }

        if ($conflictBooking->exists()) {
            return false;
        }

        // VALIDASI KONFLIK DENGAN BOOKING SLOTS
        $conflictSlot = DB::table('booking_slots')
            ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
            ->where('booking_slots.field_id', $fieldId)
            ->where('booking_slots.booking_date', $bookingDate)
            ->where('booking_slots.status', 'booked')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('booking_slots.start_time', '<=', $startTime)
                        ->where('booking_slots.end_time', '>', $startTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('booking_slots.start_time', '<', $endTime)
                            ->where('booking_slots.end_time', '>=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('booking_slots.start_time', '>=', $startTime)
                            ->where('booking_slots.end_time', '<=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('booking_slots.start_time', '<=', $startTime)
                            ->where('booking_slots.end_time', '>=', $endTime);
                    });
            })
            ->whereNotIn('bookings.payment_status', ['expired', 'cancel', 'failed']);

        if ($excludeBookingId) {
            $conflictSlot->where('bookings.id', '!=', $excludeBookingId);
        }

        if ($conflictSlot->exists()) {
            return false;
        }

        // VALIDASI SLOT MEMBER (17-19) UNTUK PUBLIC
        $startHour = (int) Carbon::parse($startTime)->format('H');
        $endHour = (int) Carbon::parse($endTime)->format('H');

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            if (in_array($hour, [17, 18, 19])) {
                // Slot member hanya bisa dibooking oleh admin
                return false;
            }
        }

        // VALIDASI WAKTU YANG SUDAH LEWAT
        if ($isToday) {
            $currentTime = now('Asia/Jakarta');
            if ($startDateTime->lte($currentTime)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validasi khusus untuk admin booking
     */
    public function areSlotsAvailableForAdmin($fieldId, $bookingDate, $startTime, $endTime, $excludeBookingId = null)
    {
        $bookingDate = Carbon::parse($bookingDate)->format('Y-m-d');
        $startDateTime = Carbon::parse("$bookingDate $startTime");
        $endDateTime = Carbon::parse("$bookingDate $endTime");

        // Validasi waktu tidak valid
        if ($startDateTime->gte($endDateTime)) {
            return false;
        }

        // VALIDASI KONFLIK DENGAN TABEL BOOKINGS (SEMUA BOOKING)
        $conflictBooking = Booking::where('field_id', $fieldId)
            ->where('booking_date', $bookingDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<=', $startTime)
                        ->where('end_time', '>', $startTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime)
                            ->where('end_time', '>=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '>=', $startTime)
                            ->where('end_time', '<=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->whereNotIn('payment_status', ['expired', 'cancel', 'failed']);

        if ($excludeBookingId) {
            $conflictBooking->where('id', '!=', $excludeBookingId);
        }

        if ($conflictBooking->exists()) {
            return false;
        }

        // VALIDASI KONFLIK DENGAN BOOKING SLOTS
        $conflictSlot = DB::table('booking_slots')
            ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
            ->where('booking_slots.field_id', $fieldId)
            ->where('booking_slots.booking_date', $bookingDate)
            ->where('booking_slots.status', 'booked')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('booking_slots.start_time', '<=', $startTime)
                        ->where('booking_slots.end_time', '>', $startTime);
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('booking_slots.start_time', '<', $endTime)
                            ->where('booking_slots.end_time', '>=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('booking_slots.start_time', '>=', $startTime)
                            ->where('booking_slots.end_time', '<=', $endTime);
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('booking_slots.start_time', '<=', $startTime)
                            ->where('booking_slots.end_time', '>=', $endTime);
                    });
            })
            ->whereNotIn('bookings.payment_status', ['expired', 'cancel', 'failed']);

        if ($excludeBookingId) {
            $conflictSlot->where('bookings.id', '!=', $excludeBookingId);
        }

        return !$conflictSlot->exists();
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

            // Ambil semua booking yang konflik
            $bookedSlots = DB::table('bookings')
                ->where('field_id', $fieldId)
                ->where('booking_date', $date)
                ->whereNotIn('payment_status', ['expired', 'cancel', 'failed'])
                ->get(['start_time', 'end_time'])
                ->toArray();

            $slots = [];
            for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                $slotTime = sprintf('%02d:00:00', $hour);
                $slotStart = Carbon::createFromFormat('Y-m-d H:i:s', "$date $slotTime", 'Asia/Jakarta');
                $slotEnd = $slotStart->copy()->addHour();

                $isPast = $isToday && $slotEnd->lte($currentTime);
                $isMemberSlot = in_array($hour, [17, 18, 19]);

                // Cek konflik dengan booking yang ada
                $isBooked = false;
                foreach ($bookedSlots as $booking) {
                    $bookingStart = Carbon::parse($booking->start_time);
                    $bookingEnd = Carbon::parse($booking->end_time);

                    if ($slotStart->lt($bookingEnd) && $slotEnd->gt($bookingStart)) {
                        $isBooked = true;
                        break;
                    }
                }

                $slots[$slotTime] = [
                    'formatted_time' => $slotStart->format('H:i') . '-' . $slotEnd->format('H:i'),
                    'is_available' => !$isBooked && !$isPast && !$isMemberSlot
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

    public function createBooking(array $data, bool $isConfirmed = false): ?Booking
    {
        try {
            DB::beginTransaction();

            $bookingDate = Carbon::parse($data['booking_date'])->format('Y-m-d');
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);
            $field = Field::findOrFail($data['field_id']);

            Log::info('Creating booking:', [
                'field_id' => $data['field_id'],
                'booking_date' => $bookingDate,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'is_confirmed' => $isConfirmed
            ]);

            // Validasi waktu
            if ($startTime->gte($endTime)) {
                throw new \Exception("Waktu akhir harus setelah waktu awal");
            }

            // Validasi ketersediaan slot
            if (!$this->areSlotsAvailable($data['field_id'], $bookingDate, $startTime->format('H:i:s'), $endTime->format('H:i:s'))) {
                throw new \Exception("Slot waktu tidak tersedia");
            }

            // PERBAIKAN: Gunakan time_slots dari data jika tersedia, atau generate dari start/end time
            $timeSlots = $data['time_slots'] ?? [];
            
            if (empty($timeSlots)) {
                Log::info('Generating time slots from start/end time');
                $currentTime = $startTime->copy();
                while ($currentTime < $endTime) {
                    $timeSlots[] = $currentTime->format('H:i:s');
                    $currentTime->addHour();
                }
            }

            Log::info('Time slots for booking:', ['time_slots' => $timeSlots]);

            // PERBAIKAN: Hitung total harga berdasarkan slot waktu dinamis
            $totalPrice = 0;
            $slotsData = [];
            
            foreach ($timeSlots as $slot) {
                $hour = (int) Carbon::parse($slot)->format('H');
                $slotPrice = $field->getPriceByTimeSlot($hour); // Gunakan harga dinamis
                $totalPrice += $slotPrice;

                $slotStartTime = Carbon::parse($bookingDate . ' ' . $slot);
                $slotEndTime = $slotStartTime->copy()->addHour();

                $slotsData[] = [
                    'slot_time' => $slot,
                    'start_time' => $slotStartTime,
                    'end_time' => $slotEndTime,
                    'price_per_slot' => $slotPrice
                ];
            }

            $duration = count($timeSlots);

            Log::info('Calculated booking totals:', [
                'total_price' => $totalPrice,
                'duration' => $duration,
                'slots_count' => count($slotsData)
            ]);

            // Generate booking code
            $bookingCode = $isConfirmed
                ? 'BK-' . Carbon::now()->format('YmdHis') . '-' . strtoupper(uniqid())
                : 'TEMP-' . strtoupper(Str::random(8));

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
                'total_price' => $totalPrice, // PERBAIKAN: Gunakan harga dinamis
                'payment_method' => $data['payment_method'],
                'payment_status' => $isConfirmed ? 'settlement' : 'pending',
                'status' => $isConfirmed ? 'confirmed' : 'pending',
            ];

            $booking = Booking::create($bookingData);

            if (!$booking) {
                throw new \Exception('Failed to create booking');
            }

            Log::info('Booking created successfully:', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_price' => $totalPrice
            ]);

            // Selalu buat transaction record
            $this->createTransactionRecord($booking);

            // PENTING: Buat slot dengan harga jika booking dikonfirmasi
            if ($isConfirmed) {
                $this->createBookingSlotsWithPrice($booking, $slotsData);
                Log::info('Booking slots created with dynamic pricing');
            }

            // For online payment, create Midtrans transaction
            if ($data['payment_method'] === 'online' && !$isConfirmed) {
                $this->createMidtransTransaction($booking);
            }

            DB::commit();
            Log::info('Booking process completed successfully:', ['booking_id' => $booking->id]);

            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage(), [
                'data' => $data,
                'stack_trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Create transaction record for any booking
     */
    private function createTransactionRecord($booking)
    {
        // PERBAIKAN: Cek duplicate sebelum create
        $existingTransaction = Transaction::where('booking_id', $booking->id)->first();

        if ($existingTransaction) {
            Log::warning('Transaction already exists for booking:', [
                'booking_id' => $booking->id,
                'existing_transaction_id' => $existingTransaction->id,
                'existing_order_id' => $existingTransaction->order_id
            ]);
            return $existingTransaction;
        }

        // Gunakan method baru dari Transaction model
        $transactionData = [
            'order_id' => 'ORDER-' . $booking->id . '-' . time(),
            'payment_type' => $booking->payment_method === 'online' ? 'midtrans' : 'cash',
            'transaction_status' => $booking->payment_status === 'settlement' ? 'settlement' : 'pending',
            'gross_amount' => $booking->total_price,
            'payment_channel' => $booking->payment_method === 'online' ? 'Midtrans' : 'Manual',
            'transaction_time' => now(),
        ];

        try {
            return Transaction::createForBooking($booking->id, $transactionData);
        } catch (\Exception $e) {
            Log::error('Failed to create transaction record:', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'data' => $transactionData
            ]);
            throw $e;
        }
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
                'slot_time' => $currentTime->format('H:i:s'),
                'status' => 'booked'
            ]);

            $currentTime->addHour();
        }

        Log::info('Booking slots created for booking:', ['booking_id' => $booking->id]);
    }

    /**
     * Create Midtrans transaction
     */
    /**
     * Create Midtrans transaction - LENGKAPI METHOD INI
     */
    private function createMidtransTransaction($booking)
    {
        try {
            $midtransData = [
                'booking_id' => $booking->id,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'customer_phone' => $booking->customer_phone,
                'total_price' => (int)$booking->total_price,
                'items' => [
                    [
                        'id' => $booking->id,
                        'name' => "Booking {$booking->field->name}",
                        'price' => (int)$booking->total_price,
                        'quantity' => 1
                    ]
                ]
            ];

            $midtransResponse = $this->midtransService->createTransaction($midtransData);

            if ($midtransResponse) {
                $booking->update([
                    'snap_token' => $midtransResponse['token'],
                    'order_id' => $midtransResponse['order_id']
                ]);
            }

            Log::info('Midtrans transaction created for booking:', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            Log::error('Failed to create Midtrans transaction:', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Cancel booking
     */
    public function cancelBooking(Booking $booking): bool
    {
        try {
            DB::beginTransaction();

            // Update booking status
            $booking->update([
                'payment_status' => 'cancel',
                'status' => 'cancelled'
            ]);

            // Cancel related slots
            $booking->slots()->update(['status' => 'cancelled']);

            // Update transaction if exists
            if ($booking->transaction) {
                $booking->transaction->update(['transaction_status' => 'cancel']);
            }

            DB::commit();
            Log::info('Booking cancelled:', ['booking_id' => $booking->id]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cancellation failed: ' . $e->getMessage());
            return false;
        }
    }
    private function createBookingSlotsWithPrice($booking, array $slotsData)
    {
        Log::info('Creating booking slots with price:', [
            'booking_id' => $booking->id,
            'slots_count' => count($slotsData)
        ]);

        foreach ($slotsData as $slotData) {
            $bookingSlot = BookingSlot::create([
                'booking_id' => $booking->id,
                'field_id' => $booking->field_id,
                'booking_date' => $booking->booking_date,
                'slot_time' => $slotData['slot_time'],
                'start_time' => $slotData['start_time'],
                'end_time' => $slotData['end_time'],
                'price_per_slot' => $slotData['price_per_slot'], // PENTING: Simpan harga per slot
                'status' => 'booked'
            ]);

            Log::info('Booking slot created:', [
                'slot_id' => $bookingSlot->id,
                'slot_time' => $slotData['slot_time'],
                'price_per_slot' => $slotData['price_per_slot']
            ]);
        }

        Log::info('All booking slots created successfully for booking:', ['booking_id' => $booking->id]);
    }
}

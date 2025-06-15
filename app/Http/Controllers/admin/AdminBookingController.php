<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\BookingSlot;
use App\Models\Transaction;
use App\Providers\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminBookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of the bookings.
     */
    public function index(Request $request)
    {
        try {
            $query = Booking::with(['field', 'transaction']);

            // Filter by payment status
            if ($request->filled('status')) {
                $query->where('payment_status', $request->input('status'));
            }

            // Filter by field
            if ($request->filled('field_id')) {
                $query->where('field_id', $request->input('field_id'));
            }

            // Filter by date range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('booking_date', [$request->input('start_date'), $request->input('end_date')]);
            }

            // Search by customer info
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            $bookings = $query->latest()->paginate(10);

            // Log for debugging
            Log::info('Bookings query executed', [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'field_id' => $request->input('field_id'),
                'date_range' => [
                    'start' => $request->input('start_date'),
                    'end' => $request->input('end_date')
                ],
                'count' => $bookings->count(),
                'total' => $bookings->total()
            ]);

            // Get all fields for filter dropdown
            $fields = Field::all();

            return view('admin.bookings.index', compact('bookings', 'fields'));
        } catch (\Exception $e) {
            Log::error('AdminBookingController index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        try {
            $fields = Field::all();
            return view('admin.bookings.create', compact('fields'));
        } catch (\Exception $e) {
            Log::error('AdminBookingController create error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        try {
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
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // VALIDASI TAMBAHAN: Cek apakah waktu yang dipilih adalah slot member (17-19)
            $startHour = (int) Carbon::parse($request->start_time)->format('H');
            $endHour = (int) Carbon::parse($request->end_time)->format('H');

            // Blokir booking admin pada slot member jika bukan member booking
            for ($hour = $startHour; $hour < $endHour; $hour++) {
                if (in_array($hour, [17, 18, 19])) {
                    return redirect()->back()
                        ->with('error', 'Slot jam 17:00-20:00 khusus untuk member. Gunakan form Member Booking untuk slot ini.')
                        ->withInput();
                }
            }

            // Check slot availability menggunakan BookingService
            $isAvailable = $this->bookingService->areSlotsAvailable(
                $request->field_id,
                $request->booking_date,
                $request->start_time,
                $request->end_time
            );

            if (!$isAvailable) {
                return redirect()->back()
                    ->with('error', 'Slot yang dipilih sudah tidak tersedia')
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // Determine if this is a direct confirmation (for cash payments)
                $directConfirm = $request->payment_method === 'cash';

                // Create booking
                $booking = $this->bookingService->createBooking($request->all(), $directConfirm);

                if (!$booking) {
                    throw new \Exception('Gagal membuat booking');
                }

                // Buat transaction record untuk admin booking
                if ($directConfirm) {
                    Transaction::create([
                        'booking_id' => $booking->id,
                        'order_id' => 'ADMIN-' . $booking->booking_code,
                        'payment_type' => 'cash',
                        'transaction_status' => 'settlement',
                        'gross_amount' => $booking->total_price,
                        'payment_channel' => 'cash_payment',
                        'transaction_time' => now(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Booking berhasil dibuat');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('AdminBookingController store error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        try {
            $booking->load(['field', 'slots', 'transaction']);
            return view('admin.bookings.show', compact('booking'));
        } catch (\Exception $e) {
            Log::error('AdminBookingController show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        try {
            $booking->load(['field', 'slots', 'transaction']);
            return view('admin.bookings.edit', compact('booking'));
        } catch (\Exception $e) {
            Log::error('AdminBookingController edit error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_name' => 'nullable|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'payment_status' => 'nullable|in:pending,settlement,expired,cancel',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $booking->update($request->only([
                'customer_name',
                'customer_email',
                'customer_phone',
                'payment_status'
            ]));

            // If payment status is updated to settlement, update slots too
            if ($request->has('payment_status')) {
                if ($request->payment_status === 'settlement') {
                    $booking->slots()->update(['status' => 'booked']);

                    // Update transaction if exists
                    if ($booking->transaction) {
                        $booking->transaction->update(['transaction_status' => 'settlement']);
                    }
                }
                // If payment status is updated to cancelled or expired, update slots too
                elseif (in_array($request->payment_status, ['cancel', 'expired'])) {
                    $booking->slots()->update(['status' => 'cancelled']);

                    // Update transaction if exists
                    if ($booking->transaction) {
                        $booking->transaction->update(['transaction_status' => $request->payment_status]);
                    }
                }
            }

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('AdminBookingController update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update booking payment status.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,settlement,expired,cancel',
            ]);

            $booking->update(['payment_status' => $validated['status']]);

            // Update booking slots status accordingly
            if ($validated['status'] === 'settlement') {
                $booking->slots()->update(['status' => 'booked']);

                // Update transaction if exists
                if ($booking->transaction) {
                    $booking->transaction->update(['transaction_status' => 'settlement']);
                }
            } elseif (in_array($validated['status'], ['expired', 'cancel'])) {
                $booking->slots()->update(['status' => 'cancelled']);

                // Update transaction if exists
                if ($booking->transaction) {
                    $booking->transaction->update(['transaction_status' => $validated['status']]);
                }
            }

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Status booking berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('AdminBookingController updateStatus error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Booking $booking)
    {
        try {
            if ($booking->payment_status === 'settlement') {
                return redirect()->back()->with('error', 'Booking dengan status settlement tidak dapat dibatalkan');
            }

            $result = $this->bookingService->cancelBooking($booking);

            if (!$result) {
                return redirect()->back()->with('error', 'Gagal membatalkan booking');
            }

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil dibatalkan');
        } catch (\Exception $e) {
            Log::error('AdminBookingController cancel error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy(Booking $booking)
    {
        try {
            // If booking has confirmed payments, don't allow deletion
            if ($booking->transaction && $booking->payment_status === 'settlement') {
                return redirect()->back()->with('error', 'Tidak dapat menghapus booking dengan pembayaran yang sudah dikonfirmasi');
            }

            DB::beginTransaction();

            // Delete related slots first
            $booking->slots()->delete();

            // Delete related transaction if exists
            if ($booking->transaction) {
                $booking->transaction->delete();
            }

            // Delete the booking
            $booking->delete();

            DB::commit();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AdminBookingController destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a cash booking.
     */
    public function confirmCashBooking(Request $request)
    {
        $pendingBooking = Session::get('pending_cash_booking');

        if (!$pendingBooking) {
            return redirect()->route('admin.bookings.index')->with('error', 'Booking tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            foreach ($pendingBooking['selected_fields'] as $fieldId) {
                // Validate slot availability again
                $slots = $pendingBooking['selected_slots'][$fieldId];
                $startTime = Carbon::parse($slots[0]);
                $endTime = Carbon::parse(end($slots))->addHour();

                if (!$this->bookingService->areSlotsAvailable($fieldId, $pendingBooking['booking_date'], $startTime, $endTime)) {
                    throw new \Exception("Slot untuk lapangan {$fieldId} sudah tidak tersedia");
                }

                // Create booking with confirmed status
                $booking = $this->bookingService->createBooking([
                    'field_id' => $fieldId,
                    'customer_name' => $pendingBooking['customer_name'],
                    'customer_phone' => $pendingBooking['customer_phone'],
                    'customer_email' => $pendingBooking['customer_email'] ?? '',
                    'booking_date' => $pendingBooking['booking_date'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'payment_method' => 'cash'
                ], true);
            }

            Session::forget('pending_cash_booking');
            DB::commit();

            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil dikonfirmasi. Slot sekarang tidak tersedia.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal konfirmasi booking cash: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengonfirmasi booking: ' . $e->getMessage());
        }
    }

    /**
     * Store member booking for slots 17-19
     */
    /**
     * Store member booking for slots 17-19 with flexible duration
     */
    /**
     * Store member booking for slots 17-19 with flexible duration
     */
    public function storeMemberBooking(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'field_id' => 'required|exists:fields,id',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'booking_date' => 'required|date|date_format:Y-m-d',
                'start_time' => 'required|date_format:H:i:s|in:17:00:00,18:00:00,19:00:00',
                'end_time' => 'required|date_format:H:i:s|after:start_time',
                'duration_hours' => 'required|integer|min:1|max:3',
                'total_price' => 'required|numeric|min:0',
                'payment_method' => 'required|in:online,cash',
                'notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Validasi bahwa end_time tidak melebihi jam 20:00
            $endHour = (int) Carbon::parse($request->end_time)->format('H');
            if ($endHour > 20) {
                return redirect()->back()
                    ->with('error', 'Waktu selesai tidak boleh melebihi jam 20:00')
                    ->withInput();
            }

            // VALIDASI KONFLIK BOOKING YANG KOMPREHENSIF
            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);

            // Cek konflik dengan semua booking yang ada (termasuk booking user biasa)
            $conflictBooking = Booking::where('field_id', $request->field_id)
                ->where('booking_date', $request->booking_date)
                ->where(function ($query) use ($startTime, $endTime) {
                    // Cek overlap dengan berbagai skenario
                    $query->where(function ($q) use ($startTime, $endTime) {
                        // Booking baru dimulai di tengah booking yang ada
                        $q->where('start_time', '<=', $startTime->format('H:i:s'))
                            ->where('end_time', '>', $startTime->format('H:i:s'));
                    })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            // Booking baru berakhir di tengah booking yang ada
                            $q->where('start_time', '<', $endTime->format('H:i:s'))
                                ->where('end_time', '>=', $endTime->format('H:i:s'));
                        })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            // Booking baru mencakup booking yang ada
                            $q->where('start_time', '>=', $startTime->format('H:i:s'))
                                ->where('end_time', '<=', $endTime->format('H:i:s'));
                        })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            // Booking yang ada mencakup booking baru
                            $q->where('start_time', '<=', $startTime->format('H:i:s'))
                                ->where('end_time', '>=', $endTime->format('H:i:s'));
                        });
                })
                ->whereNotIn('payment_status', ['expired', 'cancel', 'failed'])
                ->first();

            if ($conflictBooking) {
                return redirect()->back()
                    ->with('error', 'Terdapat konflik dengan booking yang sudah ada. Slot waktu ' .
                        $startTime->format('H:i') . ' - ' . $endTime->format('H:i') .
                        ' sudah dibooking oleh ' . $conflictBooking->customer_name)
                    ->withInput();
            }

            // Cek juga konflik dengan booking slots
            $conflictSlot = DB::table('booking_slots')
                ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                ->where('booking_slots.field_id', $request->field_id)
                ->where('booking_slots.booking_date', $request->booking_date)
                ->where('booking_slots.status', 'booked')
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('booking_slots.start_time', '<=', $startTime->format('H:i:s'))
                            ->where('booking_slots.end_time', '>', $startTime->format('H:i:s'));
                    })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('booking_slots.start_time', '<', $endTime->format('H:i:s'))
                                ->where('booking_slots.end_time', '>=', $endTime->format('H:i:s'));
                        })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('booking_slots.start_time', '>=', $startTime->format('H:i:s'))
                                ->where('booking_slots.end_time', '<=', $endTime->format('H:i:s'));
                        })
                        ->orWhere(function ($q) use ($startTime, $endTime) {
                            $q->where('booking_slots.start_time', '<=', $startTime->format('H:i:s'))
                                ->where('booking_slots.end_time', '>=', $endTime->format('H:i:s'));
                        });
                })
                ->whereNotIn('bookings.payment_status', ['expired', 'cancel', 'failed'])
                ->first();

            if ($conflictSlot) {
                return redirect()->back()
                    ->with('error', 'Slot waktu tersebut sudah dibooking melalui sistem booking slots')
                    ->withInput();
            }

            // Gunakan database transaction untuk memastikan konsistensi data
            DB::beginTransaction();

            try {
                // Tentukan status pembayaran berdasarkan metode
                $paymentStatus = $request->payment_method === 'cash' ? 'settlement' : 'pending';

                // Buat booking member
                $booking = Booking::create([
                    'field_id' => $request->field_id,
                    'booking_code' => 'MBR-' . time() . '-' . $request->field_id,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'booking_date' => $request->booking_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'duration_hours' => $request->duration_hours,
                    'total_price' => $request->total_price,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $paymentStatus,
                    'status' => 'confirmed',
                    'payment_instruction' => $request->notes
                ]);

                // Buat transaction record untuk admin booking
                $transaction = Transaction::create([
                    'booking_id' => $booking->id,
                    'order_id' => 'ADMIN-' . $booking->booking_code,
                    'payment_type' => $request->payment_method === 'cash' ? 'cash' : 'admin_booking',
                    'transaction_status' => $paymentStatus,
                    'gross_amount' => $request->total_price,
                    'payment_channel' => $request->payment_method === 'cash' ? 'cash_payment' : 'admin_direct',
                    'transaction_time' => now(),
                ]);

                // Buat booking slots jika diperlukan
                BookingSlot::create([
                    'booking_id' => $booking->id,
                    'field_id' => $request->field_id,
                    'booking_date' => $request->booking_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'slot_time' => $request->start_time,
                    'status' => 'booked'
                ]);

                DB::commit();

                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Booking member berhasil dibuat untuk ' . $request->customer_name . ' dengan durasi ' . $request->duration_hours . ' jam');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('AdminBookingController storeMemberBooking error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Check for booking conflicts
     */
    private function hasBookingConflict($fieldId, $bookingDate, $startTime, $endTime, $excludeBookingId = null)
    {
        $startTime = Carbon::parse($startTime);
        $endTime = Carbon::parse($endTime);

        // Cek konflik dengan tabel bookings
        $query = Booking::where('field_id', $fieldId)
            ->where('booking_date', $bookingDate)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<=', $startTime->format('H:i:s'))
                        ->where('end_time', '>', $startTime->format('H:i:s'));
                })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $endTime->format('H:i:s'))
                            ->where('end_time', '>=', $endTime->format('H:i:s'));
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '>=', $startTime->format('H:i:s'))
                            ->where('end_time', '<=', $endTime->format('H:i:s'));
                    })
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime->format('H:i:s'))
                            ->where('end_time', '>=', $endTime->format('H:i:s'));
                    });
            })
            ->whereNotIn('payment_status', ['expired', 'cancel', 'failed']);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }
}

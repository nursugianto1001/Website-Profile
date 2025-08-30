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
use Illuminate\Support\Facades\Auth;
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
     * Get pricing based on hour, date, and customer type - HARGA PELATIH
     */
    private function getPriceByHour($hour, $bookingDate, $isCoach = false)
    {
        $hourInt = intval($hour);
        $date = Carbon::parse($bookingDate);
        $isWeekend = $date->isSaturday() || $date->isSunday();

        if ($isWeekend) {
            // Weekend pricing (Sabtu & Minggu)
            if ($isCoach) {
                return 40000; // Pelatih: weekend semua jam 40k
            } else {
                return 60000; // Customer biasa: weekend semua jam 60k
            }
        } else {
            // Weekday pricing (Senin-Jumat)
            if ($isCoach) {
                // Harga pelatih weekday
                if ($hourInt >= 6 && $hourInt < 12) {
                    return 30000; // Pagi: 06:00-12:00 = 30k
                } elseif ($hourInt >= 12 && $hourInt < 17) {
                    return 25000; // Siang: 12:00-17:00 = 25k
                } elseif ($hourInt >= 17 && $hourInt < 23) {
                    return 40000; // Malam: 17:00-23:00 = 40k
                }
                return 30000; // Default untuk pelatih
            } else {
                // Harga customer biasa weekday
                if ($hourInt >= 6 && $hourInt < 12) {
                    return 40000; // Pagi: 06:00-12:00 = 40k
                } elseif ($hourInt >= 12 && $hourInt < 17) {
                    return 25000; // Siang: 12:00-17:00 = 25k
                } elseif ($hourInt >= 17 && $hourInt < 23) {
                    return 60000; // Malam: 17:00-23:00 = 60k
                }
                return 40000; // Default untuk customer biasa
            }
        }
    }

    /**
     * Store a newly created booking in storage - DENGAN HARGA PELATIH
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'field_id' => 'required|exists:fields,id',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'admin_name' => 'required|string|max:255',
                'booking_date' => 'required|date|date_format:Y-m-d',
                'start_time' => 'required|date_format:H:i:s',
                'end_time' => 'required|date_format:H:i:s|after:start_time',
                'payment_method' => 'required|in:online,cash',
                'customer_type' => 'required|in:regular,coach',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);
            $isCoach = $request->customer_type === 'coach';

            // Validasi konflik booking manual
            $conflictBooking = Booking::where('field_id', $request->field_id)
                ->where('booking_date', $request->booking_date)
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
                ->whereNotIn('payment_status', ['expired', 'cancel', 'failed'])
                ->first();

            if ($conflictBooking) {
                return redirect()->back()
                    ->with('error', 'Terdapat konflik dengan booking yang sudah ada. Slot waktu ' .
                        $startTime->format('H:i') . ' - ' . $endTime->format('H:i') .
                        ' sudah dibooking oleh ' . $conflictBooking->customer_name)
                    ->withInput();
            }

            // Cek konflik dengan booking slots
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

            // Generate time slots untuk dynamic pricing
            $timeSlots = [];
            $current = $startTime->copy();
            while ($current < $endTime) {
                $timeSlots[] = $current->format('H:i:s');
                $current->addHour();
            }

            // Hitung total harga dengan dynamic pricing berdasarkan customer type
            $totalPrice = 0;
            $priceBreakdown = [];
            foreach ($timeSlots as $slot) {
                $hour = Carbon::parse($slot)->hour;
                $slotPrice = $this->getPriceByHour($hour, $request->booking_date, $isCoach);
                $totalPrice += $slotPrice;
                $priceBreakdown[] = [
                    'time_slot' => Carbon::parse($slot)->format('H:i') . '-' . Carbon::parse($slot)->addHour()->format('H:i'),
                    'price' => $slotPrice
                ];
            }

            Log::info('Admin creating booking with coach pricing:', [
                'field_id' => $request->field_id,
                'customer_type' => $request->customer_type,
                'is_coach' => $isCoach,
                'time_slots' => $timeSlots,
                'price_breakdown' => $priceBreakdown,
                'total_price' => $totalPrice,
                'admin_name' => $request->admin_name,
                'booking_date' => $request->booking_date
            ]);

            DB::beginTransaction();

            try {
                $paymentStatus = $request->payment_method === 'cash' ? 'settlement' : 'pending';

                // Buat booking dengan customer type identifier
                $bookingCode = ($isCoach ? 'COACH-' : 'ADM-') . time() . '-' . $request->field_id;

                $booking = Booking::create([
                    'field_id' => $request->field_id,
                    'booking_code' => $bookingCode,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'admin_name' => $request->admin_name,
                    'booking_date' => $request->booking_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'duration_hours' => count($timeSlots),
                    'total_price' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $paymentStatus,
                    'status' => 'confirmed',
                    'payment_instruction' => $isCoach ? 'Booking Pelatih - Harga Khusus Pelatih' : 'Booking Customer Reguler',
                    'notes' => $isCoach ? 'Booking dengan harga khusus pelatih' : null,
                ]);

                // Buat booking slots untuk setiap jam dengan harga yang sesuai
                foreach ($timeSlots as $slot) {
                    $slotStartTime = Carbon::parse($slot);
                    $slotEndTime = $slotStartTime->copy()->addHour();
                    $hour = $slotStartTime->hour;
                    $pricePerSlot = $this->getPriceByHour($hour, $request->booking_date, $isCoach);

                    BookingSlot::create([
                        'booking_id' => $booking->id,
                        'field_id' => $request->field_id,
                        'booking_date' => $request->booking_date,
                        'start_time' => $slotStartTime->format('H:i:s'),
                        'end_time' => $slotEndTime->format('H:i:s'),
                        'slot_time' => $slotStartTime->format('H:i:s'),
                        'price_per_slot' => $pricePerSlot,
                        'status' => 'booked'
                    ]);
                }

                // Buat transaction record
                Transaction::createForBooking($booking->id, [
                    'order_id' => 'ADMIN-' . $booking->booking_code,
                    'payment_type' => $request->payment_method === 'cash' ? 'cash' : 'admin_booking',
                    'transaction_status' => $paymentStatus,
                    'gross_amount' => $totalPrice,
                    'payment_channel' => $request->payment_method === 'cash' ? 'cash_payment' : 'admin_direct',
                    'transaction_time' => now(),
                ]);

                DB::commit();

                Log::info('Admin booking created successfully with coach pricing:', [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'customer_type' => $isCoach ? 'coach' : 'regular',
                    'total_price' => $booking->total_price,
                    'slots_count' => count($timeSlots),
                    'time_range' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i')
                ]);

                $customerTypeText = $isCoach ? 'pelatih (harga khusus)' : 'customer reguler';
                $successMessage = "Booking {$customerTypeText} berhasil dibuat dengan total harga Rp " . number_format($booking->total_price, 0, ',', '.');

                if ($isCoach) {
                    $successMessage .= " (Diskon khusus pelatih telah diterapkan)";
                }

                return redirect()->route('admin.bookings.index')
                    ->with('success', $successMessage);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('AdminBookingController store error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store member booking dengan dynamic pricing
     */
    public function storeMemberBooking(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'field_id' => 'required|exists:fields,id',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'admin_name' => 'required|string|max:255',
                'booking_date' => 'required|date|date_format:Y-m-d',
                'start_time' => 'required|date_format:H:i:s',
                'end_time' => 'required|date_format:H:i:s|after:start_time',
                'duration_hours' => 'required|integer|min:1',
                'total_price' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $startTime = Carbon::parse($request->start_time);
            $endTime = Carbon::parse($request->end_time);

            // Validasi konflik booking untuk member
            $conflictBooking = Booking::where('field_id', $request->field_id)
                ->where('booking_date', $request->booking_date)
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
                ->whereNotIn('payment_status', ['expired', 'cancel', 'failed'])
                ->first();

            if ($conflictBooking) {
                return redirect()->back()
                    ->with('error', 'Terdapat konflik dengan booking yang sudah ada pada slot waktu tersebut')
                    ->withInput();
            }

            // Generate time slots
            $timeSlots = [];
            $current = $startTime->copy();
            while ($current < $endTime) {
                $timeSlots[] = $current->format('H:i:s');
                $current->addHour();
            }

            // Hitung ulang harga untuk memastikan konsistensi (member menggunakan harga regular)
            $totalPrice = 0;
            foreach ($timeSlots as $slot) {
                $hour = Carbon::parse($slot)->hour;
                $slotPrice = $this->getPriceByHour($hour, $request->booking_date, false); // Member = customer regular
                $totalPrice += $slotPrice;
            }

            Log::info('Admin creating member booking:', [
                'field_id' => $request->field_id,
                'time_slots' => $timeSlots,
                'calculated_price' => $totalPrice,
                'submitted_price' => $request->total_price,
                'admin_name' => $request->admin_name
            ]);

            DB::beginTransaction();

            try {
                $booking = Booking::create([
                    'field_id' => $request->field_id,
                    'booking_code' => 'MEMBER-' . time() . '-' . $request->field_id,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'admin_name' => $request->admin_name,
                    'booking_date' => $request->booking_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'duration_hours' => $request->duration_hours,
                    'total_price' => $totalPrice, // Gunakan harga yang dihitung ulang
                    'payment_method' => 'cash',
                    'payment_status' => 'settlement',
                    'status' => 'confirmed',
                    'payment_instruction' => 'Booking Member - Bebas Jam',
                    'notes' => $request->notes,
                ]);

                // Buat booking slots untuk member
                foreach ($timeSlots as $slot) {
                    $slotStartTime = Carbon::parse($slot);
                    $slotEndTime = $slotStartTime->copy()->addHour();
                    $hour = $slotStartTime->hour;
                    $pricePerSlot = $this->getPriceByHour($hour, $request->booking_date, false);

                    BookingSlot::create([
                        'booking_id' => $booking->id,
                        'field_id' => $request->field_id,
                        'booking_date' => $request->booking_date,
                        'start_time' => $slotStartTime->format('H:i:s'),
                        'end_time' => $slotEndTime->format('H:i:s'),
                        'slot_time' => $slotStartTime->format('H:i:s'),
                        'price_per_slot' => $pricePerSlot,
                        'status' => 'booked'
                    ]);
                }

                // Buat transaction record
                Transaction::createForBooking($booking->id, [
                    'order_id' => 'ADMIN-MEMBER-' . $booking->booking_code,
                    'payment_type' => 'cash',
                    'transaction_status' => 'settlement',
                    'gross_amount' => $totalPrice,
                    'payment_channel' => 'cash_payment',
                    'transaction_time' => now(),
                ]);

                DB::commit();

                Log::info('Member booking created successfully:', [
                    'booking_id' => $booking->id,
                    'total_price' => $booking->total_price,
                    'duration' => $booking->duration_hours . ' jam'
                ]);

                return redirect()->route('admin.bookings.index')
                    ->with('success', 'Booking member berhasil dibuat dengan total harga Rp ' . number_format($booking->total_price, 0, ',', '.'));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('AdminBookingController storeMemberBooking error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
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
     * Update booking payment status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,settlement,expired,cancel',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $oldStatus = $booking->payment_status;
            $newStatus = $request->input('status');

            Log::info('Admin updating booking status:', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'admin_action' => true
            ]);

            DB::beginTransaction();

            try {
                // Update booking status
                $booking->update(['payment_status' => $newStatus]);

                // Update booking slots status accordingly
                if ($newStatus === 'settlement') {
                    $booking->slots()->update(['status' => 'booked']);

                    // Update or create transaction if not exists
                    if ($booking->transaction) {
                        $booking->transaction->update(['transaction_status' => 'settlement']);
                    } else {
                        // Create transaction for admin confirmation
                        Transaction::createForBooking($booking->id, [
                            'order_id' => 'ADMIN-CONFIRM-' . $booking->booking_code,
                            'payment_type' => 'admin_confirmation',
                            'transaction_status' => 'settlement',
                            'gross_amount' => $booking->total_price,
                            'payment_channel' => 'admin_confirm',
                            'transaction_time' => now(),
                        ]);
                    }
                } elseif (in_array($newStatus, ['expired', 'cancel'])) {
                    $booking->slots()->update(['status' => 'cancelled']);

                    // Update transaction if exists
                    if ($booking->transaction) {
                        $booking->transaction->update(['transaction_status' => $newStatus]);
                    }
                }

                DB::commit();

                $statusText = [
                    'settlement' => 'dikonfirmasi',
                    'expired' => 'kadaluarsa',
                    'cancel' => 'dibatalkan',
                    'pending' => 'pending'
                ];

                Log::info('Admin booking status updated successfully:', [
                    'booking_id' => $booking->id,
                    'new_status' => $newStatus,
                    'slots_updated' => $booking->slots->count()
                ]);

                return redirect()->route('admin.bookings.show', $booking->id)
                    ->with('success', 'Status booking berhasil ' . ($statusText[$newStatus] ?? 'diperbarui'));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('AdminBookingController updateStatus error: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Providers\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class BookingPageController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display homepage with featured fields
     */
    public function index()
    {
        $fields = Field::active()->latest()->take(4)->get();
        return view('pages.home', compact('fields'));
    }

    /**
     * Display all fields
     */
    public function fields()
    {
        $fields = Field::active()->latest()->get();
        return view('pages.fields', compact('fields'));
    }

    /**
     * Display field details
     */
    public function fieldDetail(Field $field)
    {
        return view('pages.field-detail', compact('field'));
    }

    /**
     * Display booking form with field and slot selection
     */
    public function bookingForm()
    {
        $today = Carbon::today();
        $monthDates = [];
        // Ubah dari 7 hari ke 30 hari (1 bulan ke depan)
        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays($i);
            $monthDates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'day_name' => $date->format('l'),
                'formatted_date' => $date->format('d M Y')
            ];
        }

        $fields = Field::where('is_active', true)->get();
        if ($fields->isEmpty()) {
            abort(500, 'Tidak ada data lapangan aktif di database!');
        }

        $minOpeningHour = $fields->min('opening_hour') ?? 8;
        $maxClosingHour = $fields->max('closing_hour') ?? 22;

        $slots = [];
        for ($hour = $minOpeningHour; $hour < $maxClosingHour; $hour++) {
            $slotTime = sprintf('%02d:00:00', $hour);
            $slots[] = [
                'time' => $slotTime,
                'formatted_time' => Carbon::parse($slotTime)->format('H:i')
            ];
        }

        return view('pages.booking-form', [
            'monthDates' => $monthDates,
            'slots' => $slots,
            'fields' => $fields
        ]);
    }


    /**
     * Get available slots for a specific date and all fields (AJAX)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date', now('Asia/Jakarta')->format('Y-m-d'));
        $fields = \App\Models\Field::where('is_active', true)->get();
        $fieldAvailability = [];

        $currentTime = now('Asia/Jakarta');
        $isToday = $date === $currentTime->format('Y-m-d');
        $currentHour = (int) $currentTime->format('H');

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

            for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                $slotTime = sprintf('%02d:00:00', $hour);

                $isBooked = in_array($slotTime, $bookedSlots, true);
                $isPast = $isToday && ($hour <= $currentHour);

                $fieldAvailability[$fieldId][$slotTime] = !$isBooked && !$isPast;
            }
        }

        return response()->json([
            'success' => true,
            'date' => $date,
            'fieldAvailability' => $fieldAvailability,
            'current_time' => $currentTime->format('Y-m-d H:i:s')
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Get all available slots with dynamic pricing
     */
    public function getAllAvailableSlots(Request $request)
    {
        $date = $request->input('date', now('Asia/Jakarta')->format('Y-m-d'));
        $fields = \App\Models\Field::where('is_active', true)->get();
        $fieldAvailability = [];

        $currentTime = now('Asia/Jakarta');
        $isToday = $date === $currentTime->format('Y-m-d');
        $currentHour = (int) $currentTime->format('H');

        foreach ($fields as $field) {
            $fieldId = $field->id;
            $openingHour = $field->opening_hour ?? 6;
            $closingHour = $field->closing_hour ?? 23;

            // Query booking yang sudah ada
            $bookedSlotsWithCustomer = DB::table('bookings')
                ->where('field_id', $fieldId)
                ->where('booking_date', $date)
                ->where('payment_status', 'settlement')
                ->where('status', '!=', 'cancelled')
                ->whereNotIn('payment_status', ['expired', 'cancel', 'failed'])
                ->select('start_time', 'end_time', 'customer_name', 'payment_status')
                ->get();

            $pendingBookings = DB::table('bookings')
                ->where('field_id', $fieldId)
                ->where('booking_date', $date)
                ->where('payment_status', 'pending')
                ->where('status', '!=', 'cancelled')
                ->where('created_at', '>', Carbon::now()->subMinutes(30))
                ->select('start_time', 'end_time', 'customer_name', 'payment_status')
                ->get();

            $allActiveBookings = $bookedSlotsWithCustomer->merge($pendingBookings);

            for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                $slotTime = sprintf('%02d:00:00', $hour);
                $slotPrice = $this->getPriceByTimeSlot($hour);

                // Cek apakah slot sudah dibooking
                $isBooked = false;
                $customerName = null;
                $bookingStatus = null;

                foreach ($allActiveBookings as $booking) {
                    $startHour = (int) Carbon::parse($booking->start_time)->format('H');
                    $endHour = (int) Carbon::parse($booking->end_time)->format('H');

                    if ($hour >= $startHour && $hour < $endHour) {
                        $isBooked = true;
                        $customerName = $booking->customer_name;
                        $bookingStatus = $booking->payment_status;
                        break;
                    }
                }

                if ($isBooked) {
                    $fieldAvailability[$fieldId][$slotTime] = [
                        'available' => false,
                        'type' => 'booked',
                        'customer_name' => $customerName,
                        'booking_status' => $bookingStatus,
                        'price' => $slotPrice
                    ];
                    continue;
                }

                // Cek apakah waktu sudah terlewat
                $isPast = $isToday && ($hour <= $currentHour);
                if ($isPast) {
                    $fieldAvailability[$fieldId][$slotTime] = [
                        'available' => false,
                        'type' => 'past',
                        'customer_name' => 'Waktu Terlewat',
                        'price' => $slotPrice
                    ];
                    continue;
                }

                // Slot tersedia
                $fieldAvailability[$fieldId][$slotTime] = [
                    'available' => true,
                    'type' => 'available',
                    'customer_name' => null,
                    'price' => $slotPrice
                ];
            }
        }

        return response()->json([
            'success' => true,
            'date' => $date,
            'fieldAvailability' => $fieldAvailability,
            'current_time' => $currentTime->format('Y-m-d H:i:s')
        ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }

    /**
     * Process booking submission 
     */
    public function processBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date' => 'required|date|date_format:Y-m-d',
            'selected_fields' => 'required|array|min:1',
            'selected_slots' => 'required|array|min:1',
            'payment_method' => 'required|in:online',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bookingDate = $request->booking_date;
        $selectedFields = $request->selected_fields;
        $selectedSlots = $request->selected_slots;
        $paymentMethod = $request->payment_method;

        $pendingBookings = [];
        $totalPrice = 0;

        try {
            foreach ($selectedFields as $fieldId) {
                if (!isset($selectedSlots[$fieldId]) || empty($selectedSlots[$fieldId])) {
                    Log::warning("No slots selected for field {$fieldId}");
                    continue;
                }

                $timeSlots = $selectedSlots[$fieldId];
                sort($timeSlots);

                if (empty($timeSlots)) {
                    Log::warning("Empty time slots for field {$fieldId}");
                    continue;
                }

                $startTime = $timeSlots[0];
                $endTime = Carbon::parse($timeSlots[count($timeSlots) - 1])->addHour()->format('H:i:s');

                $isAvailable = $this->bookingService->areSlotsAvailable(
                    $fieldId,
                    $bookingDate,
                    $startTime,
                    $endTime
                );

                if (!$isAvailable) {
                    return redirect()->back()
                        ->with('error', 'Slot tidak tersedia untuk lapangan yang dipilih. Silakan pilih waktu lain.')
                        ->withInput();
                }

                $field = Field::findOrFail($fieldId);
                $subtotal = $field->calculateTotalPrice($timeSlots);
                $totalPrice += $subtotal;

                $pendingBookings[] = [
                    'field_id' => $fieldId,
                    'field_name' => $field->name,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_hours' => count($timeSlots),
                    'total_price' => $subtotal,
                    'payment_method' => $paymentMethod,
                    'time_slots' => $timeSlots,
                ];
            }

            if (empty($pendingBookings)) {
                return redirect()->back()
                    ->with('error', 'Tidak ada pemesanan valid yang dapat diproses.')
                    ->withInput();
            }

            $tax = 5000;
            $totalPrice += $tax;

            $tempOrderId = 'ORDER-' . time() . '-' . uniqid();

            $midtransResponse = app('App\Providers\MidtransService')->createTransaction([
                'booking_id' => $tempOrderId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price' => (int)$totalPrice,
                'items' => [
                    [
                        'id' => 'BOOKING',
                        'name' => 'Booking Lapangan',
                        'price' => (int)$totalPrice,
                        'quantity' => 1
                    ]
                ]
            ]);

            if (!$midtransResponse) {
                return redirect()->back()
                    ->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.')
                    ->withInput();
            }

            $sessionData = [
                'bookings' => $pendingBookings,
                'total_price' => $totalPrice,
                'tax' => $tax,
                'snap_token' => $midtransResponse['token'],
                'order_id' => $midtransResponse['order_id'],
                'temp_order_id' => $tempOrderId,
                'created_at' => now()->toISOString()
            ];

            Session::put('pending_booking_data', $sessionData);

            $verifySession = Session::get('pending_booking_data');
            if (!$verifySession) {
                return redirect()->back()
                    ->with('error', 'Gagal menyimpan data sementara. Silakan coba lagi.')
                    ->withInput();
            }

            return redirect()->route('booking.payment.pending');
        } catch (\Exception $e) {
            Log::error('Booking process error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }


    private function sendWhatsAppConfirmation($data)
    {
        try {
            $message = "📌 *Konfirmasi Booking Berhasil* \n\n" .
                "Nama: {$data['customer_name']}\n" .
                "No. HP: {$data['customer_phone']}\n" .
                "Total Pembayaran: Rp " . number_format($data['total_price'], 0, ',', '.') . "\n\n" .
                "Silakan selesaikan pembayaran melalui link berikut:\n" .
                route('booking.payment', ['booking' => $data['booking_ids'][0]]);

            app('App\Services\WhatsAppService')->sendMessage(
                $data['customer_phone'],
                $message
            );
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WA: ' . $e->getMessage());
        }
    }

    /**
     * Display payment page
     */
    public function payment(Request $request, Booking $booking)
    {
        $bookingIds = Session::get('booking_ids', [$booking->id]);
        $bookings = Booking::with('field')->whereIn('id', $bookingIds)->get();
        $totalBookings = $bookings->count();
        $totalPrice = $bookings->sum('total_price');
        $tax = 5000;
        $snapToken = $booking->snap_token;
        return view('pages.payment', compact('bookings', 'totalBookings', 'totalPrice', 'tax', 'snapToken'));
    }


    /**
     * Display booking success page - PERBAIKAN
     */
    public function bookingSuccess(Request $request, $booking = null)
    {
        $bookingIds = session('booking_ids', []);
        if (empty($bookingIds) && $booking) {
            $bookingIds = explode(',', $booking);
        } elseif (empty($bookingIds) && $request->has('booking')) {
            $bookingIds = explode(',', $request->booking);
        }
        if (empty($bookingIds)) {
            return redirect()->route('booking.form')->with('error', 'Data booking tidak ditemukan.');
        }

        $bookings = Booking::with(['field', 'slots'])->whereIn('id', $bookingIds)->get();

        if ($bookings->isEmpty()) {
            return redirect()->route('booking.form')->with('error', 'Data booking tidak ditemukan di database.');
        }

        $totalBookings = $bookings->count();
        $totalPrice = $bookings->sum('total_price');
        $tax = 5000;
        Session::forget('booking_ids');

        return view('pages.booking-success', compact('bookings', 'totalBookings', 'totalPrice', 'tax'));
    }


    /**
     * Handle payment success dari Midtrans - DIPERBAIKI
     */
    public function handlePaymentSuccess(Request $request)
    {
        $orderId = $request->input('order_id');
        $pendingData = Session::get('pending_booking_data');

        if (!$pendingData) {
            return redirect()->route('booking.form')
                ->with('error', 'Data booking tidak ditemukan. Session mungkin sudah expired. Silakan buat booking baru.');
        }

        if (!isset($pendingData['bookings']) || !is_array($pendingData['bookings'])) {
            Session::forget('pending_booking_data');
            return redirect()->route('booking.form')
                ->with('error', 'Data booking tidak valid. Silakan buat booking baru.');
        }

        DB::beginTransaction();
        try {
            $bookingIds = [];

            foreach ($pendingData['bookings'] as $index => $bookingData) {
                if (!isset($bookingData['field_id'], $bookingData['booking_date'], $bookingData['start_time'], $bookingData['end_time'])) {
                    continue;
                }

                $isAvailable = $this->bookingService->areSlotsAvailable(
                    $bookingData['field_id'],
                    $bookingData['booking_date'],
                    $bookingData['start_time'],
                    $bookingData['end_time']
                );

                if (!$isAvailable) {
                    DB::rollBack();
                    Session::forget('pending_booking_data');
                    return redirect()->route('booking.form')
                        ->with('error', 'Slot yang dipilih sudah tidak tersedia. Silakan pilih waktu lain.');
                }

                // Bangun ulang array time_slots jika perlu
                if (!isset($bookingData['time_slots']) || empty($bookingData['time_slots'])) {
                    $startTime = Carbon::parse($bookingData['start_time']);
                    $endTime = Carbon::parse($bookingData['end_time']);
                    $timeSlots = [];
                    $current = $startTime->copy();
                    while ($current < $endTime) {
                        $timeSlots[] = $current->format('H:i:s');
                        $current->addHour();
                    }
                    $bookingData['time_slots'] = $timeSlots;
                }

                // Buat/commit booking
                $booking = $this->bookingService->createBooking($bookingData, true);

                if (!$booking) {
                    throw new \Exception("Gagal membuat booking untuk field {$bookingData['field_id']}");
                }

                $booking->update([
                    'booking_code' => $orderId,
                    'order_id' => $orderId,
                    'snap_token' => $pendingData['snap_token'] ?? null,
                    'payment_status' => 'settlement',
                    'status' => 'confirmed'
                ]);

                $bookingIds[] = $booking->id;
            }

            if (empty($bookingIds)) {
                throw new \Exception('Tidak ada booking yang berhasil dibuat');
            }

            DB::commit();

            Session::forget('pending_booking_data');
            Session::put('booking_ids', $bookingIds);

            // Redirect ke halaman sukses (booking-success)
            return redirect()->route('booking.success', ['booking' => implode(',', $bookingIds)])
                ->with('success', 'Pembayaran berhasil! Booking Anda telah dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::forget('pending_booking_data');
            return redirect()->route('booking.form')
                ->with('error', 'Terjadi kesalahan saat menyimpan booking: ' . $e->getMessage());
        }
    }


    /**
     * Handle pembayaran belum selesai dari Midtrans
     */
    public function unfinishPayment(Request $request)
    {
        $bookingIds = Session::get('booking_ids', []);
        if (empty($bookingIds)) {
            return redirect()->route('home')->with('error', 'Booking tidak ditemukan.');
        }
        Session::forget('booking_ids');
        $successUrl = URL::signedRoute('booking.success', ['booking' => implode(',', $bookingIds)]);
        return redirect()->to($successUrl)
            ->with('multipleBookings', count($bookingIds) > 1)
            ->with('totalBookings', count($bookingIds))
            ->with('warning', 'Pembayaran Anda masih dalam proses. Anda akan menerima konfirmasi setelah pembayaran selesai.');
    }

    /**
     * Handle pembayaran error dari Midtrans
     */
    public function errorPayment(Request $request)
    {
        // Hapus data pending dari session
        Session::forget('pending_booking_data');

        return redirect()->route('booking.form')
            ->with('success', 'Pembayaran dibatalkan. Slot waktu tetap tersedia untuk pemesanan.');
    }

    /**
     * Handle notifikasi pembayaran dari Midtrans
     */
    public function handlePaymentNotification(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        Log::info('Midtrans Notification: ', $payload);

        $booking = Booking::where('booking_code', $orderId)->first();
        if (!$booking) {
            $bookingId = explode('-', $orderId)[1] ?? null;
            $booking = Booking::find($bookingId);
            if ($booking && empty($booking->booking_code)) {
                $booking->booking_code = $orderId;
            }
        }

        if (!$booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $booking->payment_status = 'settlement';
                $booking->status = 'confirmed';
                $booking->slots()->update(['status' => 'booked']);
            } elseif ($transactionStatus == 'pending') {
                $booking->payment_status = 'pending';
                // Slots tetap pending, tidak diubah
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $booking->payment_status = 'cancel';
                $booking->status = 'cancelled';
                // PENTING: Bebaskan slots yang dibatalkan
                $booking->slots()->update(['status' => 'cancelled']);
            }

            $booking->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing payment notification: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Notifikasi berhasil diproses']);
    }

    public function paymentPending(Request $request)
    {
        $pendingData = Session::get('pending_booking_data');

        if (!$pendingData) {
            return redirect()->route('booking.form')->with('error', 'Data booking tidak ditemukan. Silakan buat booking baru.');
        }

        // Cek apakah data sudah expired (misalnya 30 menit)
        $createdAt = Carbon::parse($pendingData['created_at']);
        if ($createdAt->diffInMinutes(now()) > 30) {
            Session::forget('pending_booking_data');
            return redirect()->route('booking.form')->with('error', 'Session booking telah expired. Silakan buat booking baru.');
        }

        return view('pages.payment-pending', [
            'bookings' => $pendingData['bookings'],
            'totalPrice' => $pendingData['total_price'],
            'snapToken' => $pendingData['snap_token']
        ]);
    }

    public function handlePaymentPending(Request $request)
    {
        $orderId = $request->input('order_id');

        Log::info('Payment pending handler called:', ['order_id' => $orderId]);

        return redirect()->route('booking.form')
            ->with('warning', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi atau coba lagi.');
    }

    /**
     * Get price based on time slot
     */
    private function getPriceByTimeSlot($hour)
    {
        if ($hour >= 6 && $hour < 12) {
            return 40000; // Pagi: 06:00-12:00
        } elseif ($hour >= 12 && $hour < 17) {
            return 25000; // Siang: 12:00-17:00
        } elseif ($hour >= 17 && $hour < 23) {
            return 60000; // Malam: 17:00-23:00
        }
        return 40000; // Default price
    }

    /**
     * Calculate total price based on selected time slots
     */
    private function calculateTotalPrice($fieldId, $timeSlots)
    {
        $field = Field::findOrFail($fieldId);
        $totalPrice = 0;

        foreach ($timeSlots as $slot) {
            $hour = (int) Carbon::parse($slot)->format('H');
            $slotPrice = $field->getPriceByTimeSlot($hour);
            $totalPrice += $slotPrice;
        }

        return $totalPrice;
    }
}

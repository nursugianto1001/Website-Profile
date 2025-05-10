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
        $weeklyDates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $weeklyDates[] = [
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

        return view('pages.booking-form', compact('weeklyDates', 'slots', 'fields'));
    }

    /**
     * Get available slots for a specific date and all fields (AJAX)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date', now('Asia/Jakarta')->format('Y-m-d'));
        $fields = \App\Models\Field::where('is_active', true)->get();
        $fieldAvailability = [];

        // Pastikan waktu server benar
        $currentTime = now('Asia/Jakarta');
        $isToday = $date === $currentTime->format('Y-m-d');
        $currentHour = (int) $currentTime->format('H');

        // Debug info
        Log::info("Current date: {$date}, Server time: {$currentTime->format('Y-m-d H:i:s')}, isToday: " . ($isToday ? 'true' : 'false'));
        // Di dalam method getAvailableSlots() - BookingPageController.php
        Log::info("IsToday: " . ($isToday ? 'YES' : 'NO') . " | Current Hour: $currentHour | Date: $date");

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
                Log::info("Processing hour: $hour");
                $slotTime = sprintf('%02d:00:00', $hour);
                $isBooked = in_array($slotTime, $bookedSlots, true);

                // PAKSAKAN slot yang jam-nya < jam sekarang untuk jadi false
                $isPast = $isToday && ($hour <= $currentHour);

                // Debug slot 14 dan 15
                if ($isToday && ($hour == 14 || $hour == 15)) {
                    Log::info("Slot {$hour}:00: currentHour={$currentHour}, isPast=" . ($isPast ? 'true' : 'false'));
                }

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
            'payment_method' => 'required|in:online,cash',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bookingDate = $request->booking_date;
        $selectedFields = $request->selected_fields;
        $selectedSlots = $request->selected_slots;
        $paymentMethod = $request->payment_method;

        // Di processBooking (cash)
        if ($paymentMethod === 'cash') {
            $today = now()->toDateString();
            if ($bookingDate !== $today) {
                return back()->with('error', 'Pembayaran cash hanya untuk jadwal hari ini.');
            }

            // Panggil service untuk invoice gabungan
            $invoiceResult = $this->bookingService->generateCashInvoiceWhatsApp([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $bookingDate,
                'selected_fields' => $selectedFields,
                'selected_slots' => $selectedSlots,
            ]);
            $waUrl = $invoiceResult['wa_url'];
            $totalPrice = $invoiceResult['total_price'];

            Session::put('pending_cash_booking', [
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $bookingDate,
                'selected_fields' => $selectedFields,
                'selected_slots' => $selectedSlots,
                'payment_method' => $paymentMethod
            ]);

            return view('pages.payment-cash', [
                'waUrl' => $waUrl,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $bookingDate,
                'selected_fields' => $selectedFields,
                'selected_slots' => $selectedSlots,
                'totalPrice' => $totalPrice,
            ]);
        }

        // --- Flow ONLINE tetap seperti biasa ---
        $bookings = [];
        $totalPrice = 0;
        DB::beginTransaction();
        try {
            foreach ($selectedFields as $fieldId) {
                if (!isset($selectedSlots[$fieldId]) || empty($selectedSlots[$fieldId])) continue;
                $timeSlots = $selectedSlots[$fieldId];
                sort($timeSlots);
                if (empty($timeSlots)) continue;
                $startTime = $timeSlots[0];
                $endTime = Carbon::parse($timeSlots[count($timeSlots) - 1])->addHour()->format('H:i:s');
                $isAvailable = $this->bookingService->areSlotsAvailable(
                    $fieldId,
                    $bookingDate,
                    $startTime,
                    $endTime
                );
                if (!$isAvailable) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Lapangan {$fieldId} tidak tersedia untuk slot waktu yang dipilih. Silakan pilih slot waktu lain.")->withInput();
                }
                $bookingData = [
                    'field_id' => $fieldId,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'payment_method' => $paymentMethod
                ];
                $booking = $this->bookingService->createBooking($bookingData);
                if (!$booking) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Gagal membuat booking untuk lapangan {$fieldId}. Silakan coba lagi.")->withInput();
                }
                $bookings[] = $booking;
                $totalPrice += $booking->total_price;
            }
            DB::commit();

            // Pembayaran Online: buat transaksi Midtrans
            if ($paymentMethod === 'online') {
                $orderId = 'ORD-MULTI-' . implode('-', array_map(fn($b) => $b->id, $bookings)) . '-' . time();
                $midtransResponse = app('App\Providers\MidtransService')->createTransaction([
                    'booking_id' => implode(',', array_map(fn($b) => $b->id, $bookings)),
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'total_price' => (int)$totalPrice,
                    'items' => [
                        [
                            'id' => 'MULTI',
                            'name' => 'Multi Booking',
                            'price' => (int)$totalPrice,
                            'quantity' => 1
                        ]
                    ]
                ]);
                foreach ($bookings as $booking) {
                    $booking->update([
                        'snap_token' => $midtransResponse['token'],
                        'booking_code' => $midtransResponse['order_id']
                    ]);
                }
                Session::put('booking_ids', array_map(fn($b) => $b->id, $bookings));
                $paymentUrl = URL::signedRoute('booking.payment', ['booking' => $bookings[0]->id]);
                return redirect()->to($paymentUrl);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Konfirmasi booking cash oleh admin (atau endpoint khusus)
     */
    public function confirmCashBooking(Request $request)
    {
        $data = Session::get('pending_cash_booking');
        if (!$data) {
            return redirect('/')->with('error', 'Data booking tidak ditemukan.');
        }

        $bookings = [];
        $totalPrice = 0;
        DB::beginTransaction();
        try {
            foreach ($data['selected_fields'] as $fieldId) {
                if (!isset($data['selected_slots'][$fieldId]) || empty($data['selected_slots'][$fieldId])) continue;
                $timeSlots = $data['selected_slots'][$fieldId];
                sort($timeSlots);
                if (empty($timeSlots)) continue;
                $startTime = $timeSlots[0];
                $endTime = Carbon::parse($timeSlots[count($timeSlots) - 1])->addHour()->format('H:i:s');
                $isAvailable = $this->bookingService->areSlotsAvailable(
                    $fieldId,
                    $data['booking_date'],
                    $startTime,
                    $endTime
                );
                if (!$isAvailable) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Lapangan {$fieldId} tidak tersedia untuk slot waktu yang dipilih. Silakan pilih slot waktu lain.");
                }
                $bookingData = [
                    'field_id' => $fieldId,
                    'customer_name' => $data['customer_name'],
                    'customer_email' => $data['customer_email'],
                    'customer_phone' => $data['customer_phone'],
                    'booking_date' => $data['booking_date'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'payment_method' => 'cash'
                ];
                $booking = $this->bookingService->createBooking($bookingData);
                if (!$booking) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Gagal membuat booking untuk lapangan {$fieldId}. Silakan coba lagi.");
                }
                $bookings[] = $booking;
                $totalPrice += $booking->total_price;
            }
            DB::commit();
            Session::forget('pending_cash_booking');
            Session::put('booking_ids', array_map(fn($b) => $b->id, $bookings));
            $successUrl = URL::signedRoute('booking.success', ['booking' => implode(',', array_map(fn($b) => $b->id, $bookings))]);
            return redirect()->to($successUrl)
                ->with('multipleBookings', count($bookings) > 1)
                ->with('totalBookings', count($bookings));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking cash confirm error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        $snapToken = $booking->snap_token;
        return view('pages.payment', compact('bookings', 'totalBookings', 'totalPrice', 'snapToken'));
    }

    /**
     * Display booking success page
     */
    public function bookingSuccess(Request $request, $booking = null)
    {
        $bookingIds = session('booking_ids', []);
        if (empty($bookingIds) && $booking) {
            $bookingIds = explode(',', $booking);
        } elseif (empty($bookingIds) && $request->has('booking')) {
            $bookingIds = explode(',', $request->booking);
        }
        $bookings = Booking::with('field')->whereIn('id', $bookingIds)->get();
        if ($bookings->isEmpty()) {
            return redirect('/')->with('error', 'Data booking tidak ditemukan.');
        }
        $totalBookings = $bookings->count();
        $totalPrice = $bookings->sum('total_price');
        return view('pages.booking-success', compact('bookings', 'totalBookings', 'totalPrice'));
    }

    /**
     * Handle payment finish callback dari Midtrans
     */
    public function finishPayment(Request $request)
    {
        $bookingIds = Session::get('booking_ids', []);
        $orderId = $request->input('order_id');
        if (empty($bookingIds)) {
            return redirect()->route('home')->with('error', 'Booking tidak ditemukan.');
        }
        foreach ($bookingIds as $id) {
            $booking = Booking::with(['field', 'slots'])->find($id);
            if ($booking) {
                if (empty($booking->booking_code) && !empty($orderId)) {
                    $booking->booking_code = $orderId;
                }
                if ($booking->payment_status == 'pending') {
                    $booking->payment_status = 'settlement';
                    $booking->status = 'confirmed';
                }
                $booking->save();
            }
        }
        Session::forget('booking_ids');
        $successUrl = URL::signedRoute('booking.success', ['booking' => implode(',', $bookingIds)]);
        return redirect()->to($successUrl)
            ->with('multipleBookings', count($bookingIds) > 1)
            ->with('totalBookings', count($bookingIds));
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
        $bookingIds = Session::get('booking_ids', []);
        if (empty($bookingIds)) {
            return redirect()->route('home')->with('error', 'Booking tidak ditemukan.');
        }
        foreach ($bookingIds as $id) {
            $booking = Booking::find($id);
            if ($booking) {
                $booking->status = 'failed';
                $booking->save();
            }
        }
        Session::forget('booking_ids');
        return redirect()->route('booking.form')
            ->with('error', 'Pembayaran gagal. Silakan coba booking kembali.');
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
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $booking->payment_status = 'settlement';
            $booking->status = 'confirmed';
        } elseif ($transactionStatus == 'pending') {
            $booking->payment_status = 'pending';
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $booking->payment_status = 'failed';
            $booking->status = 'cancelled';
        }
        $booking->save();
        return response()->json(['message' => 'Notifikasi berhasil diproses']);
    }
}

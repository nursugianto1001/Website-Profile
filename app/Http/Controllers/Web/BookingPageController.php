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

        // Generate 7 hari ke depan
        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $weeklyDates[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'day_name' => $date->format('l'),
                'formatted_date' => $date->format('d M Y')
            ];
        }

        // Ambil field aktif
        $fields = Field::where('is_active', true)->get();

        // Jika tidak ada field, tampilkan error di view
        if ($fields->isEmpty()) {
            abort(500, 'Tidak ada data lapangan aktif di database!');
        }

        // Ambil jam operasional minimum dan maksimum dari SEMUA field aktif
        $minOpeningHour = $fields->min('opening_hour') ?? 8;
        $maxClosingHour = $fields->max('closing_hour') ?? 22;

        // Generate slot waktu (misal 08:00-22:00)
        $slots = [];
        for ($hour = $minOpeningHour; $hour < $maxClosingHour; $hour++) {
            $slotTime = sprintf('%02d:00:00', $hour);
            $slots[] = [
                'time' => $slotTime,
                'formatted_time' => Carbon::parse($slotTime)->format('H:i')
            ];
        }

        // Kirim ke view
        return view('pages.booking-form', compact('weeklyDates', 'slots', 'fields'));
    }


    /**
     * Get available slots for a specific date and all fields (AJAX)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $fields = Field::where('is_active', true)->get();

        $fieldAvailability = [];

        foreach ($fields as $field) {
            $fieldId = $field->id;
            $fieldAvailability[$fieldId] = [];

            // Ambil jam operasional dari kolom integer
            $openingHour = $field->opening_hour ?? 8;
            $closingHour = $field->closing_hour ?? 22;

            // Query slot yang sudah dibooking
            $bookedSlots = DB::table('booking_slots')
                ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                ->where('bookings.field_id', $fieldId)
                ->where('bookings.booking_date', $date)
                ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                ->pluck('booking_slots.slot_time')
                ->toArray();

            // Generate slot waktu dan cek ketersediaan
            for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                $slotTime = sprintf('%02d:00:00', $hour);
                $fieldAvailability[$fieldId][$slotTime] = !in_array($slotTime, $bookedSlots);
            }
        }

        return response()->json([
            'success' => true,
            'fieldAvailability' => $fieldAvailability
        ]);
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

        $bookings = [];
        $totalPrice = 0;

        DB::beginTransaction();
        try {
            foreach ($selectedFields as $fieldId) {
                if (!isset($selectedSlots[$fieldId]) || empty($selectedSlots[$fieldId])) {
                    continue;
                }

                // Sort slot waktu
                $timeSlots = $selectedSlots[$fieldId];
                sort($timeSlots);

                if (empty($timeSlots)) {
                    continue;
                }

                // Hitung waktu mulai dan selesai
                $startTime = $timeSlots[0];
                $endTime = Carbon::parse($timeSlots[count($timeSlots) - 1])->addHour()->format('H:i:s');

                // Cek ketersediaan
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

                // Buat booking
                $bookingData = [
                    'field_id' => $fieldId,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'payment_method' => $request->payment_method
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

            // Proses pembayaran
            if ($request->payment_method === 'online') {
                $mainBooking = $bookings[0];
                Session::put('booking_ids', array_map(function ($booking) {
                    return $booking->id;
                }, $bookings));

                // Redirect ke halaman pembayaran dengan signed URL
                $paymentUrl = URL::signedRoute('booking.payment', ['booking' => $mainBooking->id]);
                return redirect()->to($paymentUrl);
            } else {
                // Redirect ke halaman sukses dengan signed URL
                $successUrl = URL::signedRoute('booking.success', ['booking' => $bookings[0]->id]);
                return redirect()->to($successUrl)
                    ->with('multipleBookings', true)
                    ->with('totalBookings', count($bookings));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display payment page
     */
    public function payment(Request $request, Booking $booking)
    {
        // Validasi signature oleh middleware 'signed'

        $totalBookings = Session::get('totalBookings', 1);
        $totalPrice = $booking->total_price;

        return view('pages.payment', [
            'booking' => $booking,
            'totalBookings' => $totalBookings,
            'totalPrice' => $totalPrice,
            'snapToken' => $booking->snap_token,
            'clientKey' => config('midtrans.client_key')
        ]);
    }

    /**
     * Display booking success page
     */
    public function bookingSuccess(Request $request, Booking $booking)
    {
        // Validasi signature oleh middleware 'signed'

        $booking->load(['field', 'slots']);
        $multipleBookings = Session::get('multipleBookings', false);
        $totalBookings = Session::get('totalBookings', 1);

        return view('pages.booking-success', compact('booking', 'multipleBookings', 'totalBookings'));
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

        $booking = Booking::with(['field', 'slots'])->findOrFail($bookingIds[0]);

        if (empty($booking->booking_code) && !empty($orderId)) {
            $booking->booking_code = $orderId;
            Log::info('Setting booking code: ' . $orderId);
        }

        if ($booking->payment_status == 'pending') {
            $booking->payment_status = 'settlement';
            $booking->status = 'confirmed';
            $booking->save();
        }

        Session::forget('booking_ids');

        // Redirect ke halaman sukses dengan signed URL
        $successUrl = URL::signedRoute('booking.success', ['booking' => $booking->id]);

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

        $booking = Booking::findOrFail($bookingIds[0]);
        Session::forget('booking_ids');

        // Redirect ke halaman sukses dengan signed URL
        $successUrl = URL::signedRoute('booking.success', ['booking' => $booking->id]);

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

        // Update status pembayaran
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

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Providers\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans payment notification
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleNotification(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification', $payload);

        $orderId = $payload['order_id'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $paymentType = $payload['payment_type'] ?? '';

        // PERBAIKAN: Cari transaction berdasarkan order_id
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            // PERBAIKAN: Cari booking berdasarkan snap_token atau booking_code
            // Asumsi order_id format: BOOKING-{booking_id}-{timestamp}
            $bookingId = $this->extractBookingIdFromOrderId($orderId);
            $booking = Booking::find($bookingId);

            // Alternatif: Cari berdasarkan snap_token jika tersimpan
            if (!$booking) {
                $booking = Booking::where('snap_token', 'LIKE', "%{$orderId}%")->first();
            }

            // Alternatif: Cari berdasarkan booking_code
            if (!$booking) {
                $booking = Booking::where('booking_code', $orderId)->first();
            }

            if (!$booking) {
                Log::error('Booking not found for order ID: ' . $orderId);
                return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
            }

            // Buat transaksi baru
            $transaction = Transaction::create([
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'payment_type' => $paymentType,
                'transaction_status' => $transactionStatus,
                'gross_amount' => $booking->total_price,
                'payment_channel' => $payload['payment_channel'] ?? null,
                'transaction_time' => now(),
            ]);

            Log::info('New transaction created:', [
                'transaction_id' => $transaction->id,
                'booking_id' => $booking->id,
                'order_id' => $orderId
            ]);
        } else {
            // Update transaksi yang sudah ada
            $transaction->update([
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'payment_channel' => $payload['payment_channel'] ?? null,
            ]);

            Log::info('Existing transaction updated:', [
                'transaction_id' => $transaction->id,
                'status' => $transactionStatus
            ]);
        }

        // Update booking payment status
        $paymentStatus = 'pending';
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $paymentStatus = 'settlement';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $paymentStatus = 'expired';
        }

        $transaction->booking->update(['payment_status' => $paymentStatus]);

        Log::info('Payment status updated:', [
            'booking_id' => $transaction->booking->id,
            'payment_status' => $paymentStatus
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Extract booking ID from order ID
     */
    private function extractBookingIdFromOrderId($orderId)
    {
        // Contoh format: BOOKING-123-1640995200
        if (preg_match('/BOOKING-(\d+)-\d+/', $orderId, $matches)) {
            return $matches[1];
        }

        // Contoh format lain: ORDER-123-1640995200
        if (preg_match('/ORDER-(\d+)-\d+/', $orderId, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

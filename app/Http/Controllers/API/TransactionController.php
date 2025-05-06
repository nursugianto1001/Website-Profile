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
        $fraudStatus = $payload['fraud_status'] ?? '';
        $paymentType = $payload['payment_type'] ?? '';

        // Find the related booking by order ID
        $booking = Booking::whereHas('transaction', function ($query) use ($orderId) {
            $query->where('order_id', $orderId);
        })->first();

        if (!$booking) {
            Log::error('Booking not found for order ID: ' . $orderId);
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        // Update transaction status
        $transaction = $booking->transaction;
        $transaction->update([
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
        ]);

        // Update booking payment status based on transaction status
        $paymentStatus = 'pending';

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $paymentStatus = 'settlement';
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $paymentStatus = 'expired';
            // Update booking slots status to cancelled
            $booking->slots()->update(['status' => 'cancelled']);
        } elseif ($transactionStatus == 'pending') {
            $paymentStatus = 'pending';
        }

        $booking->update(['payment_status' => $paymentStatus]);

        Log::info('Payment status updated: ' . $paymentStatus . ' for booking ID: ' . $booking->id);

        return response()->json(['success' => true]);
    }
}

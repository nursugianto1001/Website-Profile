<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;
    protected $is3ds;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->isProduction = config('midtrans.is_production', false);
        $this->is3ds = config('midtrans.is_3ds', true);

        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$is3ds = $this->is3ds;
    }

    /**
     * Get Midtrans client key
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * Create Midtrans Snap transaction
     */
    public function createTransaction($params)
    {
        try {
            if (empty($this->serverKey)) {
                Log::error('Midtrans Error: Server key is not configured');
                return null;
            }

            // ✅ PERBAIKAN: Generate order_id yang konsisten
            $orderId = 'ORDER-' . $params['booking_id'] . '-' . time();

            $transaction_details = [
                'order_id' => $orderId,
                'gross_amount' => abs((int)$params['total_price']),
            ];

            // ✅ PERBAIKAN: Process item details dengan benar
            $item_details = [];
            foreach ($params['items'] as $item) {
                $item_details[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => abs((int)$item['price']),
                    'quantity' => abs((int)$item['quantity'])
                ];
            }

            // Recalculate gross_amount to ensure it matches sum of items
            $total = 0;
            foreach ($item_details as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            $transaction_details['gross_amount'] = $total;

            $customer_details = [
                'first_name' => $params['customer_name'],
                'email' => $params['customer_email'],
                'phone' => $params['customer_phone'],
            ];

            $transaction_data = [
                'transaction_details' => $transaction_details,
                'item_details' => $item_details,
                'customer_details' => $customer_details,
            ];

            Log::info('Midtrans Request: ' . json_encode($transaction_data));
            $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);
            Log::info('Midtrans Response: ' . $snapToken);

            return [
                'token' => $snapToken,
                'order_id' => $orderId  // ✅ Return order_id yang benar
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cancel transaction in Midtrans
     */
    public function cancelTransaction($orderId)
    {
        try {
            $response = \Midtrans\Transaction::cancel($orderId);
            return $response;
        } catch (\Exception $e) {
            Log::error('Midtrans Cancel Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transaction status from Midtrans
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $response = \Midtrans\Transaction::status($orderId);
            return $response;
        } catch (\Exception $e) {
            Log::error('Midtrans Status Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get transaction notification from Midtrans
     */
    public function parseNotification($input)
    {
        try {
            $notification = new \Midtrans\Notification();
            return [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'fraud_status' => $notification->fraud_status,
                'payment_type' => $notification->payment_type,
                'gross_amount' => $notification->gross_amount,
                'transaction_time' => $notification->transaction_time,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return [
                'order_id' => $input['order_id'] ?? null,
                'transaction_status' => $input['transaction_status'] ?? null,
                'fraud_status' => $input['fraud_status'] ?? null,
                'payment_type' => $input['payment_type'] ?? null,
                'gross_amount' => $input['gross_amount'] ?? null,
                'transaction_time' => $input['transaction_time'] ?? null,
            ];
        }
    }
}

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
        $this->isProduction = config('midtrans.is_production', true);
        $this->is3ds = config('midtrans.is_3ds', true);

        // Validate key format
        if ($this->isProduction) {
            if (empty($this->serverKey) || strpos($this->serverKey, 'Mid-server-') !== 0) {
                Log::error('Midtrans Error: Invalid production server key format. Should start with Mid-server-');
                throw new \Exception('Invalid Midtrans production server key format');
            }
            if (empty($this->clientKey) || strpos($this->clientKey, 'Mid-client-') !== 0) {
                Log::error('Midtrans Error: Invalid production client key format. Should start with Mid-client-');
                throw new \Exception('Invalid Midtrans production client key format');
            }
        }

        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$is3ds = $this->is3ds;

        // Add logging
        Log::info('Midtrans Configuration:', [
            'is_production' => $this->isProduction,
            'server_key_exists' => !empty($this->serverKey),
            'client_key_exists' => !empty($this->clientKey),
            'server_key_prefix' => substr($this->serverKey, 0, 10) . '...',
            'client_key_prefix' => substr($this->clientKey, 0, 10) . '...'
        ]);
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

            // Log the Midtrans environment
            $baseUrl = \Midtrans\Config::$isProduction ? 
                'https://app.midtrans.com/snap/v1/' : 
                'https://app.sandbox.midtrans.com/snap/v1/';
            Log::info('Midtrans Environment:', [
                'base_url' => $baseUrl,
                'server_key' => substr($this->serverKey, 0, 8) . '...',
                'is_production' => \Midtrans\Config::$isProduction
            ]);

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
            
            // Add more detailed logging
            Log::info('Midtrans Config Status:', [
                'is_production' => \Midtrans\Config::$isProduction,
                'server_key' => substr(\Midtrans\Config::$serverKey, 0, 8) . '...',
                'is_3ds' => \Midtrans\Config::$is3ds
            ]);
            
            $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);
            Log::info('Midtrans Response Token: ' . $snapToken);

            return [
                'token' => $snapToken,
                'order_id' => $orderId
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            Log::error('Midtrans Error Stack Trace: ' . $e->getTraceAsString());
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

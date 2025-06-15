<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'order_id',
        'payment_type',
        'transaction_status',
        'gross_amount',
        'payment_channel',
        'transaction_time',
    ];

    protected $casts = [
        'gross_amount' => 'float',
        'transaction_time' => 'datetime',
    ];

    /**
     * Get the booking that owns the transaction
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Create transaction with duplicate prevention
     */
    public static function createForBooking($bookingId, array $data)
    {
        // Cek apakah transaction sudah ada untuk booking ini
        $existing = self::where('booking_id', $bookingId)->first();
        
        if ($existing) {
            Log::warning('Attempted to create duplicate transaction', [
                'booking_id' => $bookingId,
                'existing_transaction_id' => $existing->id,
                'existing_order_id' => $existing->order_id,
                'attempted_data' => $data
            ]);
            return $existing;
        }

        // Set booking_id
        $data['booking_id'] = $bookingId;
        
        try {
            $transaction = self::create($data);
            
            Log::info('Transaction created successfully', [
                'transaction_id' => $transaction->id,
                'booking_id' => $bookingId,
                'order_id' => $transaction->order_id
            ]);
            
            return $transaction;
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate key error jika ada unique constraint
            if ($e->getCode() === '23000' || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                Log::warning('Duplicate transaction caught by database constraint', [
                    'booking_id' => $bookingId,
                    'error' => $e->getMessage()
                ]);
                return self::where('booking_id', $bookingId)->first();
            }
            
            Log::error('Failed to create transaction', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            throw $e;
        }
    }

    /**
     * Check if transaction exists for booking
     */
    public static function existsForBooking($bookingId)
    {
        return self::where('booking_id', $bookingId)->exists();
    }

    /**
     * Get transaction by booking ID
     */
    public static function getByBookingId($bookingId)
    {
        return self::where('booking_id', $bookingId)->first();
    }

    /**
     * Update or create transaction for booking
     */
    public static function updateOrCreateForBooking($bookingId, array $data)
    {
        $data['booking_id'] = $bookingId;
        
        return self::updateOrCreate(
            ['booking_id' => $bookingId],
            $data
        );
    }
}

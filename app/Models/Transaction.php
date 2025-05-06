<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'booking_date',
        'start_time',
        'end_time',
        'duration_hours',
        'total_price',
        'payment_method',
        'payment_status',
        'snap_token',
        'booking_code',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_hours' => 'integer',
        'total_price' => 'float',
    ];

    /**
     * Get the field that owns the booking
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Get all slots for this booking
     */
    public function slots()
    {
        return $this->hasMany(BookingSlot::class);
    }

    /**
     * Get the transaction for this booking
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Scope a query to only include pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope a query to only include paid bookings
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope a query to only include confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include active bookings (pending or paid)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('payment_status', ['pending', 'paid']);
    }
}

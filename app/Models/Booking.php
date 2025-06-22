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
        'admin_name',
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
        'payment_instruction',
        'order_id',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_hours' => 'integer',
        'total_price' => 'decimal:2',
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
     * Calculate total price from booking slots (untuk verifikasi)
     */
    public function getTotalPriceFromSlotsAttribute()
    {
        return $this->slots()->sum('price_per_slot');
    }

    /**
     * Get price breakdown by time category
     */
    public function getPriceBreakdownAttribute()
    {
        return [
            'morning' => $this->slots()->where('price_per_slot', 40000)->sum('price_per_slot'),
            'afternoon' => $this->slots()->where('price_per_slot', 25000)->sum('price_per_slot'),
            'evening' => $this->slots()->where('price_per_slot', 60000)->sum('price_per_slot'),
            'total' => $this->slots()->sum('price_per_slot'),
        ];
    }

    /**
     * Check if booking has dynamic pricing data
     */
    public function hasDynamicPricing()
    {
        return $this->slots()->where('price_per_slot', '>', 0)->exists();
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Get booking time range as string
     */
    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
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
        return $query->whereIn('payment_status', ['paid', 'settlement']);
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
        return $query->whereIn('payment_status', ['pending', 'paid', 'settlement']);
    }

    /**
     * Scope untuk booking hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }

    /**
     * Scope untuk booking yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', today());
    }
}

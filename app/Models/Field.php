<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_hour',
        'image_url',
        'opening_hour',
        'closing_hour',
        'is_active',
    ];

    protected $casts = [
        'price_per_hour' => 'float',
        'is_active' => 'boolean',
        'opening_hour' => 'integer',
        'closing_hour' => 'integer',
    ];

    /**
     * Get all bookings for this field
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all booking slots for this field
     */
    public function bookingSlots()
    {
        return $this->hasMany(BookingSlot::class);
    }

    /**
     * Get active fields
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

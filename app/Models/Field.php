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
        'price_morning',      // Tambahkan ini
        'price_afternoon',    // Tambahkan ini
        'price_evening',      // Tambahkan ini
        'image_url',
        'opening_hour',
        'closing_hour',
        'is_active',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'price_morning' => 'decimal:2',     // Tambahkan ini
        'price_afternoon' => 'decimal:2',   // Tambahkan ini
        'price_evening' => 'decimal:2',     // Tambahkan ini
        'is_active' => 'boolean',
        'opening_hour' => 'integer',
        'closing_hour' => 'integer',
    ];

    /**
     * Get price based on time slot (sistem harga dinamis)
     */
    public function getPriceByTimeSlot($hour)
    {
        if ($hour >= 6 && $hour < 12) {
            return $this->price_morning ?? 40000; // Pagi: 06:00-12:00
        } elseif ($hour >= 12 && $hour < 17) {
            return $this->price_afternoon ?? 25000; // Siang: 12:00-17:00
        } elseif ($hour >= 17 && $hour < 23) {
            return $this->price_evening ?? 60000; // Malam: 17:00-23:00
        }
        return $this->price_per_hour; // Default fallback
    }

    /**
     * Get price configuration for display
     */
    public function getPriceConfiguration()
    {
        return [
            'morning' => ['start' => 6, 'end' => 12, 'price' => $this->price_morning ?? 40000],
            'afternoon' => ['start' => 12, 'end' => 17, 'price' => $this->price_afternoon ?? 25000],
            'evening' => ['start' => 17, 'end' => 23, 'price' => $this->price_evening ?? 60000],
        ];
    }

    /**
     * Calculate total price for multiple time slots
     */
    public function calculateTotalPrice($timeSlots)
    {
        $totalPrice = 0;
        foreach ($timeSlots as $slot) {
            $hour = (int) \Carbon\Carbon::parse($slot)->format('H');
            $totalPrice += $this->getPriceByTimeSlot($hour);
        }
        return $totalPrice;
    }

    public function getPriceTiersAttribute()
    {
        return [
            'morning' => [
                'time' => '06:00 - 12:00',
                'price' => $this->price_morning ?? 40000,
                'formatted' => 'Rp ' . number_format($this->price_morning ?? 40000, 0, ',', '.')
            ],
            'afternoon' => [
                'time' => '12:00 - 17:00',
                'price' => $this->price_afternoon ?? 25000,
                'formatted' => 'Rp ' . number_format($this->price_afternoon ?? 25000, 0, ',', '.')
            ],
            'evening' => [
                'time' => '17:00 - 23:00',
                'price' => $this->price_evening ?? 60000,
                'formatted' => 'Rp ' . number_format($this->price_evening ?? 60000, 0, ',', '.')
            ]
        ];
    }

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

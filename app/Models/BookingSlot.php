<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'field_id',
        'booking_date',
        'slot_time',
        'start_time',
        'end_time',
        'status',
        'price_per_slot', // TAMBAH INI - Penting untuk harga dinamis
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price_per_slot' => 'decimal:2', // TAMBAH INI
    ];

    /**
     * Automatically calculate and set price when creating slot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bookingSlot) {
            // Auto-calculate price jika belum diset dan ada slot_time
            if (!$bookingSlot->price_per_slot && $bookingSlot->slot_time) {
                $hour = (int) Carbon::parse($bookingSlot->slot_time)->format('H');
                $field = $bookingSlot->field;
                if ($field) {
                    $bookingSlot->price_per_slot = $field->getPriceByTimeSlot($hour, $bookingSlot->booking_date);
                }
            }
        });

        static::updating(function ($bookingSlot) {
            // Recalculate price jika slot_time berubah
            if ($bookingSlot->isDirty('slot_time') && $bookingSlot->slot_time) {
                $hour = (int) Carbon::parse($bookingSlot->slot_time)->format('H');
                $field = $bookingSlot->field;
                if ($field) {
                    $bookingSlot->price_per_slot = $field->getPriceByTimeSlot($hour, $bookingSlot->booking_date);
                }
            }
        });
    }

    /**
     * Get the booking that owns the slot
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the field that owns the slot
     */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_per_slot, 0, ',', '.');
    }

    /**
     * Get price category based on amount
     */
    public function getPriceCategoryAttribute()
    {
        // Check if weekend
        if ($this->booking_date) {
            $isWeekend = Carbon::parse($this->booking_date)->isSaturday() ||
                Carbon::parse($this->booking_date)->isSunday();
            if ($isWeekend && $this->price_per_slot == 60000) {
                return 'weekend';
            }
        }

        if ($this->price_per_slot == 40000) {
            return 'morning';
        } elseif ($this->price_per_slot == 25000) {
            return 'afternoon';
        } elseif ($this->price_per_slot == 60000) {
            return 'evening';
        }

        return 'custom';
    }

    /**
     * Get time range for this slot
     */
    public function getTimeRangeAttribute()
    {
        if ($this->slot_time) {
            $start = Carbon::parse($this->slot_time)->format('H:i');
            $end = Carbon::parse($this->slot_time)->addHour()->format('H:i');
            return $start . ' - ' . $end;
        }
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    /**
     * Scope a query to only include booked slots
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    /**
     * Scope untuk filter berdasarkan rentang harga
     */
    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price_per_slot', [$minPrice, $maxPrice]);
    }

    /**
     * Scope untuk slot dengan harga pagi
     */
    public function scopeMorningPrice($query)
    {
        return $query->where('price_per_slot', 40000);
    }

    /**
     * Scope untuk slot dengan harga siang
     */
    public function scopeAfternoonPrice($query)
    {
        return $query->where('price_per_slot', 25000);
    }

    /**
     * Scope untuk slot dengan harga malam
     */
    public function scopeEveningPrice($query)
    {
        return $query->where('price_per_slot', 60000);
    }

    /**
     * Check if the slot is available for the given date and time range
     */
    public static function isSlotAvailable($fieldId, $date, $startTime, $endTime)
    {
        return !static::where('field_id', $fieldId)
            ->where('booking_date', $date)
            ->where('status', 'booked')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();
    }

    /**
     * Calculate total revenue for specific date range
     */
    public static function calculateRevenue($startDate, $endDate, $fieldId = null)
    {
        $query = static::where('status', 'booked')
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        return $query->sum('price_per_slot');
    }

    /**
     * Get revenue breakdown by price category
     */
    public static function getRevenueBreakdown($startDate, $endDate, $fieldId = null)
    {
        $query = static::where('status', 'booked')
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        return [
            'morning' => $query->clone()->where('price_per_slot', 40000)->sum('price_per_slot'),
            'afternoon' => $query->clone()->where('price_per_slot', 25000)->sum('price_per_slot'),
            'evening' => $query->clone()->where('price_per_slot', 60000)->sum('price_per_slot'),
            'total' => $query->sum('price_per_slot')
        ];
    }
}

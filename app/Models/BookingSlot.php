<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'field_id',
        'booking_date',
        'slot_time', // Tambahkan field ini
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

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
     * Scope a query to only include booked slots
     */
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
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
}

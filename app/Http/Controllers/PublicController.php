<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\Booking;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function home()
    {
        $facilities = Facility::all();
        $featuredPosters = Gallery::getFeatured('poster', 3);
        $featuredDocumentations = Gallery::getFeatured('documentation', 6);

        // TAMBAHAN: Ambil data lapangan dan booking
        $fields = Field::where('is_active', true)->get();

        // Ambil booking untuk hari ini dan besok
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $bookedFields = Booking::with(['field'])
            ->where('payment_status', 'settlement')
            ->whereIn('booking_date', [$today->format('Y-m-d'), $tomorrow->format('Y-m-d')])
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Siapkan data ketersediaan lapangan seperti di booking form
        $fieldAvailability = [];
        $currentTime = now('Asia/Jakarta');

        foreach ($fields as $field) {
            $fieldId = $field->id;
            $openingHour = $field->opening_hour ?? 8;
            $closingHour = $field->closing_hour ?? 22;

            // Untuk hari ini dan besok
            foreach ([$today, $tomorrow] as $date) {
                $dateString = $date->format('Y-m-d');
                $isToday = $dateString === $currentTime->format('Y-m-d');
                $currentHour = (int) $currentTime->format('H');

                // Ambil slot yang sudah dibooking menggunakan booking_slots seperti di BookingPageController
                $bookedSlots = DB::table('booking_slots')
                    ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                    ->where('bookings.field_id', $fieldId)
                    ->where('bookings.booking_date', $dateString)
                    ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                    ->pluck('booking_slots.slot_time')
                    ->map(function ($time) {
                        return Carbon::parse($time)->format('H:i:s');
                    })
                    ->toArray();

                for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                    $slotTime = sprintf('%02d:00:00', $hour);
                    $isBooked = in_array($slotTime, $bookedSlots);
                    $isPast = $isToday && ($hour <= $currentHour);

                    $fieldAvailability[$fieldId][$dateString][$slotTime] = [
                        'available' => !$isBooked && !$isPast,
                        'booked' => $isBooked,
                        'past' => $isPast
                    ];
                }
            }
        }

        // Siapkan slot waktu
        $minOpeningHour = $fields->min('opening_hour') ?? 8;
        $maxClosingHour = $fields->max('closing_hour') ?? 22;

        $slots = [];
        for ($hour = $minOpeningHour; $hour < $maxClosingHour; $hour++) {
            $slotTime = sprintf('%02d:00:00', $hour);
            $slots[] = [
                'time' => $slotTime,
                'formatted_time' => Carbon::parse($slotTime)->format('H:i')
            ];
        }

        return view('public.home', compact(
            'facilities',
            'featuredPosters',
            'featuredDocumentations',
            'bookedFields',
            'fields',
            'fieldAvailability',
            'slots',
            'today',
            'tomorrow'
        ));
    }

    public function about()
    {
        return view('public.about');
    }

    public function facilities()
    {
        $facilities = Facility::all();
        return view('public.facilities', compact('facilities'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function gallery()
    {
        $posters = Gallery::getByType('poster');
        $documentations = Gallery::getByType('documentation');
        return view('public.gallery', compact('posters', 'documentations'));
    }
}

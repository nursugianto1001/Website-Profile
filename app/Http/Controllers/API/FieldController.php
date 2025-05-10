<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FieldController extends Controller
{
    /**
     * Ambil semua field aktif
     */
    public function index()
    {
        try {
            $fields = Field::active()->get();

            return response()->json([
                'success' => true,
                'data' => $fields
            ]);
        } catch (\Exception $e) {
            Log::error('FieldController index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    /**
     * Detail satu field
     */
    public function show(Field $field)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $field
            ]);
        } catch (\Exception $e) {
            Log::error('FieldController show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    /**
     * Ambil slot tersedia untuk semua field pada tanggal tertentu
     */
    public function getAllAvailableSlots(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::today('Asia/Jakarta')->format('Y-m-d'));

            // Validasi format tanggal
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid date format'
                ], 400);
            }

            $fields = Field::where('is_active', true)->get();
            $fieldAvailability = [];

            // Cek apakah tanggal yang diminta adalah hari ini
            $isToday = $date === Carbon::today('Asia/Jakarta')->format('Y-m-d');
            $currentTime = Carbon::now('Asia/Jakarta');
            $currentHour = (int) $currentTime->format('H');

            // Debug log
            Log::info("API getAllAvailableSlots - date: $date, isToday: " . ($isToday ? 'Y' : 'N') . ", currentHour: $currentHour");

            foreach ($fields as $field) {
                $fieldId = $field->id;
                $fieldAvailability[$fieldId] = [];

                // Ambil jam operasional dari kolom integer
                $openingHour = $field->opening_hour ?? 8;
                $closingHour = $field->closing_hour ?? 22;

                // Query slot yang sudah dibooking untuk field & tanggal ini
                $bookedSlots = DB::table('booking_slots')
                    ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                    ->where('bookings.field_id', $fieldId)
                    ->where('bookings.booking_date', $date)
                    ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                    ->pluck('booking_slots.slot_time')
                    ->toArray();

                // Generate slot waktu (misal: 08:00:00, 09:00:00, dst)
                for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                    $slotTime = sprintf('%02d:00:00', $hour);
                    $isBooked = in_array($slotTime, $bookedSlots);

                    // Cek jika slot sudah lewat waktu untuk hari ini
                    $isPast = $isToday && ($hour < $currentHour);

                    // Slot tersedia jika TIDAK dibooking DAN TIDAK sudah lewat
                    $fieldAvailability[$fieldId][$slotTime] = !$isBooked && !$isPast;

                    // Log untuk debug slot 14:00 dan 15:00
                    if ($isToday && ($hour == 14 || $hour == 15)) {
                        Log::info("Slot $hour:00:00 - isBooked: " . ($isBooked ? 'Y' : 'N') .
                            ", isPast: " . ($isPast ? 'Y' : 'N') .
                            ", available: " . ($fieldAvailability[$fieldId][$slotTime] ? 'Y' : 'N'));
                    }
                }
            }

            return response()->json([
                'success' => true,
                'date' => $date,
                'fieldAvailability' => $fieldAvailability,
                'current_time' => $currentTime->format('Y-m-d H:i:s')
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        } catch (\Exception $e) {
            Log::error('FieldController getAllAvailableSlots error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    /**
     * Ambil slot mingguan untuk satu field (opsional)
     */
    public function getWeeklySlots(Field $field)
    {
        try {
            $weeklySlots = [];
            $openingHour = $field->opening_hour ?? 8;
            $closingHour = $field->closing_hour ?? 22;
            $currentTime = Carbon::now('Asia/Jakarta');
            $currentHour = (int) $currentTime->format('H');
            $today = Carbon::today('Asia/Jakarta')->format('Y-m-d');

            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::today('Asia/Jakarta')->addDays($i)->format('Y-m-d');
                $isToday = $date === $today;

                // Query slot yang sudah dibooking
                $bookedSlots = DB::table('booking_slots')
                    ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                    ->where('bookings.field_id', $field->id)
                    ->where('bookings.booking_date', $date)
                    ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                    ->pluck('booking_slots.slot_time')
                    ->toArray();

                $slots = [];
                for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                    $slotTime = sprintf('%02d:00:00', $hour);
                    $isBooked = in_array($slotTime, $bookedSlots);
                    $isPast = $isToday && ($hour < $currentHour);

                    $slots[$slotTime] = !$isBooked && !$isPast;
                }

                $weeklySlots[] = [
                    'date' => $date,
                    'slots' => $slots
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $weeklySlots
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        } catch (\Exception $e) {
            Log::error('FieldController getWeeklySlots error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

    /**
     * Ambil slot tersedia untuk satu field pada tanggal tertentu (opsional)
     */
    public function getAvailableSlots(Request $request, Field $field)
    {
        try {
            $date = $request->input('date', Carbon::today('Asia/Jakarta')->format('Y-m-d'));
            $openingHour = $field->opening_hour ?? 8;
            $closingHour = $field->closing_hour ?? 22;

            $isToday = $date === Carbon::today('Asia/Jakarta')->format('Y-m-d');
            $currentTime = Carbon::now('Asia/Jakarta');
            $currentHour = (int) $currentTime->format('H');

            $bookedSlots = DB::table('booking_slots')
                ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                ->where('bookings.field_id', $field->id)
                ->where('bookings.booking_date', $date)
                ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                ->pluck('booking_slots.slot_time')
                ->toArray();

            $slots = [];
            for ($hour = $openingHour; $hour < $closingHour; $hour++) {
                $slotTime = sprintf('%02d:00:00', $hour);
                $isBooked = in_array($slotTime, $bookedSlots);
                $isPast = $isToday && ($hour < $currentHour);

                $slots[$slotTime] = !$isBooked && !$isPast;
            }

            return response()->json([
                'success' => true,
                'date' => $date,
                'slots' => $slots,
                'current_time' => $currentTime->format('Y-m-d H:i:s')
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        } catch (\Exception $e) {
            Log::error('FieldController getAvailableSlots error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
}

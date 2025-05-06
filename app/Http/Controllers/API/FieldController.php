<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Providers\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Get all active fields
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = Field::active()->get();

        return response()->json([
            'success' => true,
            'data' => $fields
        ]);
    }

    /**
     * Get field details
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        return response()->json([
            'success' => true,
            'data' => $field
        ]);
    }

    /**
     * Get available slots for a field on a specific date
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function getAvailableSlots(Request $request, Field $field)
    {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');
        $slots = $this->bookingService->getAvailableSlots($field->id, $date);

        return response()->json([
            'success' => true,
            'data' => [
                'field' => $field,
                'date' => $date,
                'slots' => $slots
            ]
        ]);
    }

    /**
     * Get weekly available slots for a field
     *
     * @param  \App\Models\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function getWeeklySlots(Field $field)
    {
        $weeklySlots = [];
        $today = Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $dayData = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->format('l'),
                'slots' => $this->bookingService->getAvailableSlots($field->id, $date->format('Y-m-d')),
            ];

            $weeklySlots[] = $dayData;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'field' => $field,
                'weekly_slots' => $weeklySlots
            ]
        ]);
    }
}

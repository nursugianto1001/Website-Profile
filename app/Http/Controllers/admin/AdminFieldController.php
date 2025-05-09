<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Field;
use App\Models\Booking;
use App\Models\BookingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminFieldController extends Controller
{
    /**
     * Display a listing of the fields.
     */
    public function index()
    {
        try {
            $fields = Field::all();
            return view('admin.fields.index', compact('fields'));
        } catch (\Exception $e) {
            Log::error('AdminFieldController index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new field.
     */
    public function create()
    {
        return view('admin.fields.create');
    }

    /**
     * Store a newly created field in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_per_hour' => 'required|numeric|min:0',
                'opening_hour' => 'required|integer|min:0|max:23',
                'closing_hour' => 'required|integer|min:0|max:23|gt:opening_hour',
                'is_active' => 'boolean',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $fieldData = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price_per_hour' => $validated['price_per_hour'],
                'opening_hour' => $validated['opening_hour'],
                'closing_hour' => $validated['closing_hour'],
                'is_active' => $request->has('is_active'),
            ];

            // Handle image upload if provided
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/fields', $imageName);
                $fieldData['image_url'] = '/storage/fields/' . $imageName;
            }

            $field = Field::create($fieldData);

            return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('AdminFieldController store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified field.
     */
    public function show(Field $field)
    {
        try {
            // Ambil data ketersediaan slot hari ini
            $date = Carbon::today()->format('Y-m-d');
            $openingHour = $field->opening_hour;
            $closingHour = $field->closing_hour;
            
            $bookedSlots = DB::table('booking_slots')
                ->join('bookings', 'booking_slots.booking_id', '=', 'bookings.id')
                ->where('bookings.field_id', $field->id)
                ->where('bookings.booking_date', $date)
                ->whereNotIn('bookings.payment_status', ['expired', 'cancel'])
                ->pluck('booking_slots.slot_time')
                ->toArray();
            
            return view('admin.fields.show', compact('field', 'date', 'bookedSlots'));
        } catch (\Exception $e) {
            Log::error('AdminFieldController show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified field.
     */
    public function edit(Field $field)
    {
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update the specified field in storage.
     */
    public function update(Request $request, Field $field)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_per_hour' => 'required|numeric|min:0',
                'opening_hour' => 'required|integer|min:0|max:23',
                'closing_hour' => 'required|integer|min:0|max:23|gt:opening_hour',
                'is_active' => 'boolean',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $fieldData = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price_per_hour' => $validated['price_per_hour'],
                'opening_hour' => $validated['opening_hour'],
                'closing_hour' => $validated['closing_hour'],
                'is_active' => $request->has('is_active'),
            ];

            // Handle image upload if provided
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/fields', $imageName);
                $fieldData['image_url'] = '/storage/fields/' . $imageName;
            }

            $field->update($fieldData);

            return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('AdminFieldController update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified field from storage.
     */
    public function destroy(Field $field)
    {
        try {
            // Cek apakah ada booking aktif
            $activeBookings = Booking::where('field_id', $field->id)
                ->whereIn('payment_status', ['pending', 'settlement'])
                ->where('booking_date', '>=', Carbon::today())
                ->exists();

            if ($activeBookings) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus lapangan karena masih ada booking aktif');
            }

            $field->delete();
            return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('AdminFieldController destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

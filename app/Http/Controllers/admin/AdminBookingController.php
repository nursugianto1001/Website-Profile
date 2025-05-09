<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\BookingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index(Request $request)
    {
        try {
            $query = Booking::with('field');

            // Filter berdasarkan pencarian
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $status = $request->input('status');
                $query->where('payment_status', $status);
            }

            $bookings = $query->latest()->paginate(10);
            
            // Log untuk debugging
            Log::info('Search query executed', [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
                'count' => $bookings->count(),
                'total' => $bookings->total()
            ]);
            
            return view('admin.bookings.index', compact('bookings'));
        } catch (\Exception $e) {
            Log::error('AdminBookingController index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update booking status.
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,settlement,expired,cancel',
            ]);

            $booking->update(['payment_status' => $validated['status']]);

            // Update booking slots status accordingly
            if ($validated['status'] == 'settlement') {
                $booking->slots()->update(['status' => 'booked']);
            } elseif (in_array($validated['status'], ['expired', 'cancel'])) {
                $booking->slots()->update(['status' => 'cancelled']);
            }

            return redirect()->route('admin.bookings.index')->with('success', 'Status booking berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('AdminBookingController updateStatus error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified booking.
     */

    public function show(Booking $booking)
    {
        try {
            $booking->load(['field', 'slots', 'transaction']);
            return view('admin.bookings.show', compact('booking'));
        } catch (\Exception $e) {
            Log::error('AdminBookingController show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Booking $booking)
    {
        try {
            if ($booking->payment_status !== 'pending') {
                return redirect()->back()->with('error', 'Hanya booking dengan status pending yang dapat dibatalkan');
            }

            $booking->update(['payment_status' => 'cancel']);

            // Update booking slots status to cancelled
            $booking->slots()->update(['status' => 'cancelled']);

            return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dibatalkan');
        } catch (\Exception $e) {
            Log::error('AdminBookingController cancel error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
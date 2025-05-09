<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Field;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Statistik untuk dashboard
        $totalBookings = Booking::count();
        $totalFields = Field::where('is_active', true)->count();
        $pendingBookings = Booking::where('payment_status', 'pending')->count();
        
        // Total pendapatan dari transaksi yang selesai
        $totalRevenue = Transaction::whereIn('transaction_status', ['settlement', 'capture'])
            ->sum('gross_amount');
        
        // Booking terbaru
        $recentBookings = Booking::with('field')
            ->latest()
            ->take(5)
            ->get();
        
        // Booking hari ini
        $todayBookings = Booking::with('field')
            ->where('booking_date', Carbon::today()->format('Y-m-d'))
            ->whereIn('payment_status', ['pending', 'settlement'])
            ->orderBy('start_time')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalBookings',
            'totalFields',
            'pendingBookings',
            'totalRevenue',
            'recentBookings',
            'todayBookings'
        ));
    }
}

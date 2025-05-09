<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminTransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index(Request $request)
    {
        try {
            $query = Transaction::with('booking');

            // Filter berdasarkan pencarian (order ID atau nama pelanggan)
            if ($request->has('search') && !empty($request->input('search'))) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('order_id', 'like', "%{$search}%")
                      ->orWhereHas('booking', function($query) use ($search) {
                          $query->where('customer_name', 'like', "%{$search}%")
                                ->orWhere('customer_email', 'like', "%{$search}%")
                                ->orWhere('customer_phone', 'like', "%{$search}%");
                      });
                });
            }

            // Filter berdasarkan status
            if ($request->has('status') && !empty($request->input('status'))) {
                $status = $request->input('status');
                $query->where('transaction_status', $status);
            }
            
            // Filter berdasarkan tanggal
            if ($request->has('date_from') && !empty($request->input('date_from'))) {
                $query->whereDate('created_at', '>=', $request->input('date_from'));
            }
            
            if ($request->has('date_to') && !empty($request->input('date_to'))) {
                $query->whereDate('created_at', '<=', $request->input('date_to'));
            }

            $transactions = $query->latest()->paginate(10);
            return view('admin.transactions.index', compact('transactions'));
        } catch (\Exception $e) {
            Log::error('AdminTransactionController index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        try {
            $transaction->load('booking.field');
            return view('admin.transactions.show', compact('transaction'));
        } catch (\Exception $e) {
            Log::error('AdminTransactionController show error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
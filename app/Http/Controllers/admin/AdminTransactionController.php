<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminTransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index(Request $request)
    {
        try {

            $query = Transaction::with(['booking', 'booking.field'])
                ->orderBy('transaction_time', 'desc');

            // Filter berdasarkan pencarian
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('order_id', 'like', "%{$search}%")
                        ->orWhereHas('booking', function ($query) use ($search) {
                            $query->where('customer_name', 'like', "%{$search}%")
                                ->orWhere('customer_email', 'like', "%{$search}%")
                                ->orWhere('customer_phone', 'like', "%{$search}%");
                        });
                });
            }

            // Filter berdasarkan status
            if ($request->filled('status')) {
                $query->where('transaction_status', $request->input('status'));
            }

            // Filter berdasarkan tanggal menggunakan transaction_time
            if ($request->filled('date_from')) {
                $query->whereDate('transaction_time', '>=', $request->input('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->whereDate('transaction_time', '<=', $request->input('date_to'));
            }

            $transactions = $query->paginate(10);

            // Hitung statistik dengan query terpisah
            $totalSettlement = Transaction::whereIn('transaction_status', ['settlement', 'capture'])->count();
            $totalPending = Transaction::where('transaction_status', 'pending')->count();
            $totalFailed = Transaction::whereIn('transaction_status', ['cancel', 'deny', 'expire'])->count();

            return view('admin.transactions.index', compact(
                'transactions',
                'totalSettlement',
                'totalPending',
                'totalFailed'
            ));
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

    /**
     * Export transactions to Excel
     */
    public function export(Request $request)
    {
        try {
            $filters = $request->only(['search', 'status', 'date_from', 'date_to']);

            $filename = 'transactions_' . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download(new TransactionsExport($filters), $filename);
        } catch (\Exception $e) {
            Log::error('Transaction export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}

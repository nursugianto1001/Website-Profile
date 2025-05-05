<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminTransactionController extends Controller
{
    /**
     * Get all transactions with pagination
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['booking', 'booking.field']);

        // Filter by transaction status
        if ($request->has('transaction_status')) {
            $query->where('transaction_status', $request->transaction_status);
        }

        // Filter by payment type
        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        // Filter by field
        if ($request->has('field_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('field_id', $request->field_id);
            });
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('transaction_time', [$request->start_date, $request->end_date]);
        }

        // Search by order ID
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('order_id', 'like', "%{$search}%");
        }

        $transactions = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Show specific transaction details
     *
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['booking', 'booking.field', 'booking.slots']);

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Create a manual transaction (for cash payments)
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createManualTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'payment_type' => 'required|string|in:cash,bank_transfer,other',
            'gross_amount' => 'required|numeric|min:0',
            'transaction_time' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = Booking::findOrFail($request->booking_id);

        // Check if transaction already exists
        if ($booking->transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction already exists for this booking'
            ], 400);
        }

        // Create transaction
        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'order_id' => 'MANUAL-' . $booking->id . '-' . time(),
            'payment_type' => $request->payment_type,
            'transaction_status' => 'settlement',
            'gross_amount' => $request->gross_amount,
            'transaction_time' => $request->transaction_time ?? now(),
            'settlement_time' => now(),
            'notes' => $request->notes,
            'payment_proof' => null, // Manual transactions don't need proof
            'is_manual' => true,
        ]);

        // Update booking status to confirmed
        $booking->update([
            'status' => 'confirmed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Manual transaction created successfully',
            'data' => $transaction->load(['booking', 'booking.field'])
        ], 201);
    }

    /**
     * Update transaction status
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validator = Validator::make($request->all(), [
            'transaction_status' => 'required|string|in:pending,settlement,cancel,deny,expire,refund',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $transaction->transaction_status;
        $newStatus = $request->transaction_status;

        // Update transaction status
        $transaction->update([
            'transaction_status' => $newStatus,
            'notes' => $request->filled('notes') ? $request->notes : $transaction->notes,
            'settlement_time' => in_array($newStatus, ['settlement']) ? now() : $transaction->settlement_time,
        ]);

        // Update booking status based on transaction status
        if ($transaction->booking) {
            $booking = $transaction->booking;

            switch ($newStatus) {
                case 'settlement':
                    $booking->update(['status' => 'confirmed']);
                    break;
                case 'cancel':
                case 'deny':
                case 'expire':
                case 'refund':
                    $booking->update(['status' => 'cancelled']);
                    break;
                default:
                    // No change for pending
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction status updated successfully',
            'data' => $transaction->fresh()->load(['booking', 'booking.field'])
        ]);
    }

    /**
     * Delete a transaction
     *
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        // Check if the transaction can be deleted
        if ($transaction->transaction_status === 'settlement') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a settled transaction'
            ], 400);
        }

        // Update associated booking status
        if ($transaction->booking) {
            $transaction->booking->update(['status' => 'cancelled']);
        }

        // Delete the transaction
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully'
        ]);
    }

    /**
     * Get transaction statistics
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getStatistics(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Total revenue
        $totalRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereBetween('transaction_time', [$startDate, $endDate])
            ->sum('gross_amount');

        // Count by payment type
        $paymentTypeCounts = Transaction::where('transaction_status', 'settlement')
            ->whereBetween('transaction_time', [$startDate, $endDate])
            ->selectRaw('payment_type, count(*) as count, sum(gross_amount) as total')
            ->groupBy('payment_type')
            ->get();

        // Count by status
        $statusCounts = Transaction::whereBetween('transaction_time', [$startDate, $endDate])
            ->selectRaw('transaction_status, count(*) as count, sum(gross_amount) as total')
            ->groupBy('transaction_status')
            ->get();

        // Daily revenue for the period
        $dailyRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereBetween('transaction_time', [$startDate, $endDate])
            ->selectRaw('DATE(transaction_time) as date, sum(gross_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $totalRevenue,
                'payment_types' => $paymentTypeCounts,
                'status_counts' => $statusCounts,
                'daily_revenue' => $dailyRevenue,
            ]
        ]);
    }

    /**
     * Verify payment proof for bank transfer transactions
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function verifyPaymentProof(Request $request, Transaction $transaction)
    {
        $validator = Validator::make($request->all(), [
            'is_verified' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($transaction->payment_type !== 'bank_transfer') {
            return response()->json([
                'success' => false,
                'message' => 'Only bank transfer payments can be verified'
            ], 400);
        }

        if ($request->is_verified) {
            // Approve the payment
            $transaction->update([
                'transaction_status' => 'settlement',
                'settlement_time' => now(),
                'notes' => $request->filled('notes') ? $request->notes : $transaction->notes,
            ]);

            // Update booking status
            if ($transaction->booking) {
                $transaction->booking->update(['status' => 'confirmed']);
            }

            $message = 'Payment proof verified and approved';
        } else {
            // Reject the payment
            $transaction->update([
                'transaction_status' => 'deny',
                'notes' => $request->filled('notes') ? $request->notes : $transaction->notes,
            ]);

            // Update booking status
            if ($transaction->booking) {
                $transaction->booking->update(['status' => 'cancelled']);
            }

            $message = 'Payment proof rejected';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $transaction->fresh()->load(['booking', 'booking.field'])
        ]);
    }
}

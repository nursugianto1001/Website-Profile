<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateTransactions extends Command
{
    protected $signature = 'transactions:cleanup-duplicates {--dry-run : Show what would be deleted without actually deleting}';
    protected $description = 'Remove duplicate transactions keeping the oldest one';

    public function handle()
    {
        $this->info('Starting cleanup of duplicate transactions...');

        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No data will be deleted');
        }

        // Find duplicates
        $duplicates = DB::table('transactions')
            ->select('booking_id', DB::raw('COUNT(*) as count'))
            ->groupBy('booking_id')
            ->having('count', '>', 1)
            ->get();

        $this->info("Found {$duplicates->count()} bookings with duplicate transactions");

        if ($duplicates->count() === 0) {
            $this->info('No duplicate transactions found!');
            return 0;
        }

        $deletedCount = 0;
        $totalDuplicates = 0;

        foreach ($duplicates as $duplicate) {
            // Keep the oldest transaction, delete the rest
            $transactions = Transaction::where('booking_id', $duplicate->booking_id)
                ->orderBy('id')
                ->get();

            $keepTransaction = $transactions->first();
            $deleteTransactions = $transactions->skip(1);

            $totalDuplicates += $deleteTransactions->count();

            $this->line("Booking ID: {$duplicate->booking_id} has {$duplicate->count} transactions");
            $this->line("  → Keeping transaction ID: {$keepTransaction->id} (Order: {$keepTransaction->order_id})");

            foreach ($deleteTransactions as $transaction) {
                $this->line("  → " . ($dryRun ? 'Would delete' : 'Deleting') . " transaction ID: {$transaction->id} (Order: {$transaction->order_id})");

                if (!$dryRun) {
                    $transaction->delete();
                    $deletedCount++;
                }
            }

            $this->newLine();
        }

        if ($dryRun) {
            $this->warn("DRY RUN COMPLETE: Would delete {$totalDuplicates} duplicate transactions.");
            $this->info('Run without --dry-run to actually delete the duplicates.');
        } else {
            $this->info("Cleanup completed. Deleted {$deletedCount} duplicate transactions.");
        }

        return 0;
    }
}

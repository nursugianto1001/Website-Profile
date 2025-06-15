<?php
// File: database/migrations/2025_06_15_add_unique_booking_id_to_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus duplicate data terlebih dahulu jika ada
            $this->removeDuplicateTransactions();
            
            // Tambahkan unique constraint pada booking_id
            $table->unique('booking_id', 'transactions_booking_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropUnique('transactions_booking_id_unique');
        });
    }

    /**
     * Remove duplicate transactions before adding unique constraint
     */
    private function removeDuplicateTransactions()
    {
        // Query untuk menghapus duplicate, keep yang pertama (oldest)
        DB::statement("
            DELETE t1 FROM transactions t1
            INNER JOIN transactions t2 
            WHERE t1.id > t2.id 
            AND t1.booking_id = t2.booking_id
        ");
    }
};

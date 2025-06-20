<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom sudah ada
        if (!Schema::hasColumn('booking_slots', 'price_per_slot')) {
            Schema::table('booking_slots', function (Blueprint $table) {
                $table->decimal('price_per_slot', 10, 2)->default(0)->after('slot_time');
            });
        }
    }

    public function down(): void
    {
        Schema::table('booking_slots', function (Blueprint $table) {
            $table->dropColumn('price_per_slot');
        });
    }
};

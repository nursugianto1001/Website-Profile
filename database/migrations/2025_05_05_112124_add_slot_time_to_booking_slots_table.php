<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_slots', function (Blueprint $table) {
            $table->time('slot_time')->after('booking_id');
        });
    }

    public function down(): void
    {
        Schema::table('booking_slots', function (Blueprint $table) {
            $table->dropColumn('slot_time');
        });
    }
};

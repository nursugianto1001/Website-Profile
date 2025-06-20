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
        Schema::table('fields', function (Blueprint $table) {
            $table->decimal('price_morning', 10, 2)->default(40000)->after('price_per_hour');
            $table->decimal('price_afternoon', 10, 2)->default(25000)->after('price_morning');
            $table->decimal('price_evening', 10, 2)->default(60000)->after('price_afternoon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn(['price_morning', 'price_afternoon', 'price_evening']);
        });
    }
};

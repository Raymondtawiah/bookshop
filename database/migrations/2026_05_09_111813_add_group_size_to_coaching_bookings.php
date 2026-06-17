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
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->integer('group_size')->default(1)->after('package');
            $table->enum('booking_type', ['individual', 'team'])->default('individual')->after('group_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->dropColumn('group_size');
            $table->dropColumn('booking_type');
        });
    }
};

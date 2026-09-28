<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->date('booking_date')->nullable()->after('order_items');
            $table->time('booking_time')->nullable()->after('booking_date');
            $table->text('booking_note')->nullable()->after('booking_time');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['booking_date', 'booking_time', 'booking_note']);
        });
    }
};

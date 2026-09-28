<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->json('booking_times')->nullable()->after('published_year');
            $table->string('booking_zoom_link')->nullable()->after('booking_times');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['booking_times', 'booking_zoom_link']);
        });
    }
};

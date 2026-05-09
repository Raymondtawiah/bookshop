<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->enum('package', ['team', 'single', 'premium'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->enum('package', ['single', 'premium'])->change();
        });
    }
};

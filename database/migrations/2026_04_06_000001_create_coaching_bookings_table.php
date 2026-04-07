<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coaching_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('interview_type');
            $table->date('interview_date');
            $table->time('interview_time');
            $table->enum('package', ['single', 'premium']);
            $table->text('notes')->nullable();
            $table->string('booking_token')->unique();
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coaching_bookings');
    }
};

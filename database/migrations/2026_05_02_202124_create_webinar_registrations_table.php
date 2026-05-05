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
        Schema::create('webinar_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webinar_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('registration_status')->default('registered');
            $table->string('payment_status')->default('pending');
            $table->string('transaction_reference')->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['webinar_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webinar_registrations');
    }
};

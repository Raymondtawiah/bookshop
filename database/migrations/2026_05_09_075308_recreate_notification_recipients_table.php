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
        // Create the notification_recipients table with proper MySQL constraints
        Schema::create('notification_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webinar_notification_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('webinar_registration_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['sent', 'failed', 'pending'])->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Add unique constraint that allows null user_id for guest registrations
            $table->unique(['webinar_notification_id', 'webinar_registration_id'], 'notification_recipient_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

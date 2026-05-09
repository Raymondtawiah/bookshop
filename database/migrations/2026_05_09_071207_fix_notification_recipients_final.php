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
        // Drop all existing unique constraints that cause issues
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->dropUnique('notification_user_unique');
            $table->dropUnique('notification_registration_unique');
            $table->dropUnique('notification_user_unique');
        });
        
        // Add a simple unique constraint that allows null user_id for guest registrations
        // This prevents duplicate notifications for the same user+registration combination
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->unique(['webinar_notification_id', 'webinar_registration_id'], 'notification_recipient_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the original constraints
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->unique(['webinar_notification_id', 'user_id', 'webinar_registration_id'], 'notification_user_unique');
            $table->unique(['webinar_notification_id', 'webinar_registration_id'], 'notification_registration_unique');
        });
    }
};

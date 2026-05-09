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
        // Drop the existing unique constraint that causes issues with guest registrations
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->dropUnique('notification_user_unique');
        });
        
        // Add a new unique constraint that allows null user_id for guest registrations
        // This ensures each registration gets each notification only once per webinar, but allows guests (user_id = null)
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->unique(['webinar_notification_id', 'webinar_registration_id'], 'notification_registration_unique');
        });
        
        // Also add a partial unique constraint for registered users (user_id is not null)
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->unique(['webinar_notification_id', 'user_id'], 'notification_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_recipients', function (Blueprint $table) {
            //
        });
    }
};

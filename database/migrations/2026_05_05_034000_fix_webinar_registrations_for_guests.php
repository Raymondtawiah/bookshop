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
        Schema::table('webinar_registrations', function (Blueprint $table) {
            // Drop the unique constraint that requires user_id
            $table->dropUnique(['webinar_id', 'user_id']);
            
            // Make user_id nullable for guest registrations
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add back columns if they were removed
            if (!Schema::hasColumn('webinar_registrations', 'full_name')) {
                $table->string('full_name')->after('user_id');
            }
            if (!Schema::hasColumn('webinar_registrations', 'email')) {
                $table->string('email')->after('full_name');
            }
            if (!Schema::hasColumn('webinar_registrations', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('webinar_registrations', 'access_token')) {
                $table->string('access_token')->nullable()->after('joined_at');
                $table->timestamp('access_token_expires_at')->nullable()->after('access_token');
                $table->index('access_token');
            }
            
            // Add unique constraint on webinar_id + email for guests
            $table->unique(['webinar_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->dropUnique(['webinar_id', 'email']);
            $table->unique(['webinar_id', 'user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};

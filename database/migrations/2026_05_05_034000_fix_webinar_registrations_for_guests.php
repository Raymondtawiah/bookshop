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
            // Step 1: Drop the foreign key constraint FIRST
            $table->dropForeign(['user_id']);
            
            // Step 2: Drop the unique constraint that requires user_id
            $table->dropUnique('webinar_registrations_webinar_id_user_id_unique');
            
            // Step 3: Make user_id nullable for guest registrations
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Step 4: Re-add the foreign key constraint (now allowing NULL)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Step 5: Add back columns if they were removed
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
            
            // Step 6: Add unique constraint on webinar_id + email for guests (email must be NOT NULL)
            $table->unique(['webinar_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            // Step 1: Drop the unique constraint on email
            $table->dropUnique(['webinar_id', 'email']);
            
            // Step 2: Drop the foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Step 3: Make user_id NOT NULL (this will fail if NULL records exist)
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Step 4: Re-add the unique constraint on user_id
            $table->unique(['webinar_id', 'user_id']);
            
            // Step 5: Re-add the foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

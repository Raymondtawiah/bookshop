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
            // Drop index first (SQLite requirement)
            $table->dropIndex('webinar_registrations_access_token_index');
            
            // Remove unused verification and token columns
            $table->dropColumn([
                'access_token',
                'access_token_expires_at', 
                'verified_at'
            ]);
            
            // Remove redundant columns that can be derived from users table
            $table->dropColumn([
                'full_name',
                'email',
                'phone'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            // Add back the columns if needed
            $table->string('access_token')->nullable();
            $table->timestamp('access_token_expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone')->nullable();
            
            // Re-add index for access token
            $table->index('access_token');
        });
    }
};

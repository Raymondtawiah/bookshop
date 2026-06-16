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
            $table->string('access_token')->nullable()->after('joined_at');
            $table->timestamp('access_token_expires_at')->nullable()->after('access_token');
            $table->index('access_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->dropIndex(['access_token']);
            $table->dropColumn(['access_token', 'access_token_expires_at']);
        });
    }
};

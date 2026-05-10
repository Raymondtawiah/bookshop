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
        // Fix foreign key constraint by updating to reference correct table
        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->dropForeign(['webinar_id']);
        });
        
        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->foreignId('webinar_id')->constrained('webinar_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->dropForeign(['webinar_id']);
        });
        
        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->foreignId('webinar_id')->constrained('webinars')->onDelete('cascade');
        });
    }
};

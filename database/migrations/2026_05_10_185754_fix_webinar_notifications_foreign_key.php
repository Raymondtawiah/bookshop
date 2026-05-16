<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Update foreign key to reference webinar_sessions instead of webinars.
     */
    public function up(): void
    {
        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->dropForeign(['webinar_id']);
        });

        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->foreign('webinar_id')
                  ->references('id')
                  ->on('webinar_sessions')
                  ->onDelete('cascade');
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
            $table->foreign('webinar_id')
                  ->references('id')
                  ->on('webinars')
                  ->onDelete('cascade');
        });
    }
};

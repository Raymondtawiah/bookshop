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
        // First drop the foreign key constraint, then the index
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->dropForeign(['notification_recipients_user_id']);
        });
        
        Schema::table('notification_recipients', function (Blueprint $table) {
            $table->dropIndex('notification_user_unique');
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

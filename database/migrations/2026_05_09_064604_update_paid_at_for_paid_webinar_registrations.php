<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update paid_at timestamp for existing paid registrations that don't have it set
        DB::statement("
            UPDATE webinar_registrations 
            SET paid_at = created_at 
            WHERE payment_status = 'paid' AND paid_at IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            //
        });
    }
};

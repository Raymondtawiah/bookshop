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
            $table->string('payment_provider')->default('stripe')->after('transaction_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->dropColumn('payment_provider');
        });
    }
};

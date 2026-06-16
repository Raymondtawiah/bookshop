<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Change payment_method from enum to string to allow 'card' value
     * and future payment methods without enum constraints.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Attempt to revert back to enum with original values (momo, bank).
     * Note: This may fail if there are 'card' values in the database.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['momo', 'bank'])->nullable()->change();
        });
    }
};

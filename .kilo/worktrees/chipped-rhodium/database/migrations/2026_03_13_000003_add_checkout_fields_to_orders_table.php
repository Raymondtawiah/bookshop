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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('email')->nullable()->after('customer_name');
            $table->string('residence')->nullable()->after('email');
            $table->string('contact', 20)->nullable()->after('residence');
            $table->enum('payment_method', ['momo', 'bank'])->nullable()->after('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['email', 'residence', 'contact', 'payment_method']);
        });
    }
};

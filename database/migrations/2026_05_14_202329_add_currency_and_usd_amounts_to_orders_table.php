<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds currency tracking and USD amount fields to orders table
     * to support dual-currency payments (GHS via Paystack, USD via Stripe)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('currency', 3)->default('GHS')->after('total_amount')
                ->comment('Transaction currency: GHS or USD');
            $table->decimal('total_amount_usd', 10, 2)->nullable()->after('currency')
                ->comment('Order total in USD (for Stripe payments)');
            $table->decimal('exchange_rate', 10, 4)->nullable()->after('total_amount_usd')
                ->comment('GHS to USD conversion rate used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['currency', 'total_amount_usd', 'exchange_rate']);
        });
    }
};
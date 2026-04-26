<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Exchange rate: 1 USD = 12.50 GHS
     * This converts all existing USD prices to GHS
     */
    public function up(): void
    {
        // Get exchange rate from config
        $rate = config('settings.usd_to_ghs_rate', 12.50);

        // Convert books prices: price_usd -> price_ghs
        if (Schema::hasColumn('books', 'price_usd')) {
            // First add new column
            Schema::table('books', function (Blueprint $table) {
                $table->decimal('price_ghs', 10, 2)->nullable()->after('price_usd');
            });

            // Copy and convert data
            DB::table('books')->update([
                'price_ghs' => DB::raw('ROUND(price_usd * '.$rate.', 2)')
            ]);

            // Drop old column and rename new one
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('price_usd');
            });

            Schema::table('books', function (Blueprint $table) {
                $table->renameColumn('price_ghs', 'price');
            });
        }

        // Convert carts prices: unit_price_usd -> unit_price_ghs
        if (Schema::hasColumn('carts', 'unit_price_usd')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->decimal('unit_price_ghs', 10, 2)->nullable()->after('unit_price_usd');
            });

            DB::table('carts')->update([
                'unit_price_ghs' => DB::raw('ROUND(unit_price_usd * '.$rate.', 2)')
            ]);

            Schema::table('carts', function (Blueprint $table) {
                $table->dropColumn('unit_price_usd');
            });

            Schema::table('carts', function (Blueprint $table) {
                $table->renameColumn('unit_price_ghs', 'unit_price');
            });
        }

        // Convert orders: update total_amount from USD to GHS, drop GHS helper columns
        if (Schema::hasColumn('orders', 'total_amount') && Schema::hasColumn('orders', 'total_amount_ghs')) {
            // Update total_amount to be in GHS
            DB::table('orders')->whereNotNull('total_amount')->update([
                'total_amount' => DB::raw('ROUND(total_amount * '.$rate.', 2)')
            ]);

            // Drop the now-redundant GHS columns
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn(['total_amount_ghs', 'exchange_rate']);
            });
        }
    }

    public function down(): void
    {
        // Reverse conversion would require storing the rate somewhere
        // For simplicity, down() just reverts column names without data conversion
        // In production, you'd want to store the conversion rate

        $rate = config('settings.usd_to_ghs_rate', 12.50);

        // Books: add back price_usd
        if (!Schema::hasColumn('books', 'price_usd')) {
            Schema::table('books', function (Blueprint $table) {
                $table->decimal('price_usd', 10, 2)->nullable()->after('price');
            });

            DB::table('books')->update([
                'price_usd' => DB::raw('ROUND(price / '.$rate.', 2)')
            ]);
        }

        // Carts: add back unit_price_usd
        if (!Schema::hasColumn('carts', 'unit_price_usd')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->decimal('unit_price_usd', 10, 2)->nullable()->after('unit_price');
            });

            DB::table('carts')->update([
                'unit_price_usd' => DB::raw('ROUND(unit_price / '.$rate.', 2)')
            ]);
        }

        // Orders: restore total_amount_ghs and exchange_rate
        if (!Schema::hasColumn('orders', 'total_amount_ghs')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('total_amount_ghs', 10, 2)->nullable()->after('total_amount');
                $table->decimal('exchange_rate', 10, 2)->nullable()->after('total_amount_ghs');
            });

            DB::table('orders')->update([
                'total_amount_ghs' => DB::raw('ROUND(total_amount / '.$rate.', 2)'),
                'exchange_rate' => $rate
            ]);
        }
    }
};

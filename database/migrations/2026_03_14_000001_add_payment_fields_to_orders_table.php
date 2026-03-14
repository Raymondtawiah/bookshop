<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('orders', 'email')) {
                $table->string('email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'contact')) {
                $table->string('contact')->nullable()->after('email');
            }
            if (!Schema::hasColumn('orders', 'residence')) {
                $table->string('residence')->nullable()->after('contact');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('residence');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'pdf_sent')) {
                $table->boolean('pdf_sent')->default(false)->after('paid_at');
            }
            if (!Schema::hasColumn('orders', 'pdf_sent_at')) {
                $table->timestamp('pdf_sent_at')->nullable()->after('pdf_sent');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number', 
                'email', 
                'contact', 
                'residence', 
                'payment_method', 
                'payment_status', 
                'paid_at',
                'pdf_sent',
                'pdf_sent_at'
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('book_offered')->default(false)->after('pdf_sent');
            $table->timestamp('book_offered_at')->nullable()->after('pdf_sent_at');
            $table->text('offer_note')->nullable()->after('book_offered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['book_offered', 'book_offered_at', 'offer_note']);
        });
    }
};

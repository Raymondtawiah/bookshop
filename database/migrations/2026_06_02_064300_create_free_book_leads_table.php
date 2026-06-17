<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('free_book_leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('book_title');
            $table->string('download_token')->unique();
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->index(['email', 'book_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('free_book_leads');
    }
};

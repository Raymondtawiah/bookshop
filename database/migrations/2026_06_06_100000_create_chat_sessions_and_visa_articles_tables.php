<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('session_token')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('session_token');
            $table->index('last_activity');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_session_id')->constrained()->onDelete('cascade');
            $table->string('role'); // user | assistant | system
            $table->text('content');
            $table->timestamps();

            $table->index('chat_session_id');
            $table->index('created_at');
        });

        Schema::create('visa_articles', function (Blueprint $table) {
            $table->id();
            $table->string('country');
            $table->string('visa_type')->nullable();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['country', 'visa_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_articles');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};

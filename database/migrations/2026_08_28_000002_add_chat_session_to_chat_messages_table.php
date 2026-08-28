<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->string('chat_session')->nullable()->after('user_id');
            $table->index(['chat_session', 'sender_type', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['chat_session', 'sender_type', 'status', 'created_at']);
            $table->dropColumn('chat_session');
        });
    }
};

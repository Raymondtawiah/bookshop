<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->string('meeting_link')->nullable()->after('amount');
            $table->timestamp('meeting_time')->nullable()->after('meeting_link');
            $table->text('meeting_notes')->nullable()->after('meeting_time');
        });
    }

    public function down(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->dropColumn(['meeting_link', 'meeting_time', 'meeting_notes']);
        });
    }
};

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
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->timestamp('email_sent_at')->nullable()->after('paid_at');
            $table->timestamp('email_failed_at')->nullable()->after('email_sent_at');
            $table->text('email_error')->nullable()->after('email_failed_at');
            $table->integer('email_attempts')->default(0)->after('email_error');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->dropColumn(['email_sent_at', 'email_failed_at', 'email_error', 'email_attempts']);
        });
    }
};

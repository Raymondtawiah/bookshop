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
            // Add soft deletes
            if (! Schema::hasColumn('webinar_registrations', 'deleted_at')) {
                $table->softDeletes();
            }

            // Add reminder tracking fields (check if they exist first)
            if (! Schema::hasColumn('webinar_registrations', 'last_reminder_sent')) {
                $table->timestamp('last_reminder_sent')->nullable()->after('email_attempts');
            }
            if (! Schema::hasColumn('webinar_registrations', 'reminder_count')) {
                $table->integer('reminder_count')->default(0)->after('last_reminder_sent');
            }

            // Add indexes for better query performance (check if they exist first)
            if (! Schema::hasIndex('webinar_registrations', 'webinar_registrations_webinar_id_payment_status_index')) {
                $table->index(['webinar_id', 'payment_status']);
            }
            if (! Schema::hasIndex('webinar_registrations', 'webinar_registrations_payment_status_registration_status_index')) {
                $table->index(['payment_status', 'registration_status']);
            }
            if (! Schema::hasIndex('webinar_registrations', 'webinar_registrations_created_at_index')) {
                $table->index(['created_at']);
            }
            if (! Schema::hasIndex('webinar_registrations', 'webinar_registrations_email_index')) {
                $table->index(['email']);
            }
            if (! Schema::hasIndex('webinar_registrations', 'webinar_registrations_full_name_index')) {
                $table->index(['full_name']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropIndex(['webinar_id_payment_status']);
            $table->dropIndex(['payment_status_registration_status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['email']);
            $table->dropIndex(['full_name']);
            $table->dropColumn(['last_reminder_sent', 'reminder_count']);
        });
    }
};

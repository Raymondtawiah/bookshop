<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('date', 'attendance_date');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('attendance_date');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejected_reason')->nullable()->after('approved_at');

            $table->index(['user_id', 'attendance_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'attendance_date', 'status']);
            $table->renameColumn('attendance_date', 'date');
            $table->dropColumn([
                'approved_by',
                'approved_at',
                'rejected_reason',
            ]);
        });
    }
};

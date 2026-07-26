<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->unsignedInteger('access_count')->default(0)->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('webinar_registrations', function (Blueprint $table) {
            $table->dropColumn('access_count');
        });
    }
};

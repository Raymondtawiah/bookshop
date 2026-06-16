<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Check if a foreign key exists by its conventional name.
     */
    protected function foreignKeyExists(string $table, string $foreignKeyName): bool
    {
        $result = DB::select('
            SELECT COUNT(*) as count
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_NAME = ?
        ', [$table, $foreignKeyName]);

        return isset($result[0]) && $result[0]->count > 0;
    }

    /**
     * Run the migrations.
     *
     * Update foreign key to reference webinar_sessions instead of webinars.
     */
    public function up(): void
    {
        $table = 'webinar_notifications';
        $foreignKey = 'webinar_notifications_webinar_id_foreign';

        if ($this->foreignKeyExists($table, $foreignKey)) {
            Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey);
            });
        }

        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->foreign('webinar_id')
                ->references('id')
                ->on('webinar_sessions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = 'webinar_notifications';
        $foreignKey = 'webinar_notifications_webinar_id_foreign';

        if ($this->foreignKeyExists($table, $foreignKey)) {
            Schema::table($table, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey);
            });
        }

        Schema::table('webinar_notifications', function (Blueprint $table) {
            $table->foreign('webinar_id')
                ->references('id')
                ->on('webinars')
                ->onDelete('cascade');
        });
    }
};

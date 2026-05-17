<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Check if a foreign key exists by its conventional name.
     *
     * @param string $table
     * @param string $foreignKeyName
     * @return bool
     */
    protected function foreignKeyExists(string $table, string $foreignKeyName): bool
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't have information_schema, use PRAGMA
            try {
                $result = DB::select("PRAGMA foreign_key_list($table)");
                return collect($result)->contains('id', $foreignKeyName);
            } catch (\Exception $e) {
                return false;
            }
        }

        // MySQL/MariaDB
        $result = DB::select("
            SELECT COUNT(*) as count
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND CONSTRAINT_NAME = ?
        ", [$table, $foreignKeyName]);

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

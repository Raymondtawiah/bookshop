<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webinar_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webinar_id')->constrained('webinar_sessions')->onDelete('cascade');
            $table->string('source');
            $table->text('additional_comments')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->index(['webinar_id', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webinar_surveys');
    }
};

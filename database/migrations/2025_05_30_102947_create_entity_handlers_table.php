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
        Schema::create('entity_handlers', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // 'mastermind', 'scheme', etc.
            $table->unsignedBigInteger('entity_id');
            $table->string('handler_class'); // Fully qualified class name
            $table->timestamps();

            $table->unique(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_handlers');
    }
};

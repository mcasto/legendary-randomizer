<?php

use App\Models\Setup;
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
        Schema::create('expects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Setup::class)->constrained()->onDelete('cascade');
            $table->string('section');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expects');
    }
};

<?php

use App\Models\Color;
use App\Models\Hero;
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
        Schema::create('hero_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hero::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Color::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_colors');
    }
};

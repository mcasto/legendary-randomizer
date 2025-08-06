<?php

use App\Models\Hero;
use App\Models\Keyword;
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
        Schema::create('hero_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hero::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Keyword::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_keywords');
    }
};

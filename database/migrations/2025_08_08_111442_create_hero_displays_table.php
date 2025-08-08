<?php

use App\Models\User;
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
        Schema::create('hero_displays', function (Blueprint $table) {
            $table->id();
            $table->string('user_data_id');
            $table->foreign('user_data_id')->references('data_id')->on('users')->onDelete('cascade');
            $table->string('bg')->default("#ffffff");
            $table->string('text')->default("#000000");
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_displays');
    }
};

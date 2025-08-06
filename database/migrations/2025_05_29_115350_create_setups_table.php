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
        Schema::create('setups', function (Blueprint $table) {
            $table->id();
            $table->integer('players');
            $table->integer('twists');
            $table->integer('schemes');
            $table->integer('masterminds');
            $table->integer('villains');
            $table->integer('henchmen');
            $table->integer('heroes');
            $table->integer('bystanders');
            $table->integer('wounds');
            $table->integer('officers');
            $table->integer('shards');
            $table->string('data_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setups');
    }
};

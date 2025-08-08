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
        Schema::create('villain_displays', function (Blueprint $table) {
            $table->id();
            $table->string('user_data_id');
            $table->foreign('user_data_id')->references('data_id')->on('users')->onDelete('cascade');
            $table->string('bg')->default("#f50000");
            $table->string('text')->default("#ffffff");
            $table->integer('order')->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villain_displays');
    }
};

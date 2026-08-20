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
        Schema::create('record_games_adults', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');

            $table->integer('stars_OfertaOEngano')->default(0);
            $table->integer('stars_SigueLaReceta')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_games_adults');
    }
};

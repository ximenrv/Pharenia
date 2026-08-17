<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
    Schema::create('record_games_children', function (Blueprint $table) {
        $table->id();
        // Relación con el email del usuario (único)
        $table->string('email')->unique();
        $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
        
        // Columnas para los récords de los juegos
        $table->integer('record_Eco')->default(0);
        $table->integer('record_Guardianes')->default(0);
        $table->integer('record_Cazador')->default(0);
        
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('record_games_childs');
    }
};

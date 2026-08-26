<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('record_games_children', function (Blueprint $table) {
            $table->id();
            
            // Permite vincular a un usuario adulto/normal o a un perfil infantil
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('child_profile_id')->nullable()->constrained('child_profile')->onDelete('cascade');
            
            // Columnas para los récords de los juegos
            $table->integer('record_Eco')->default(0);
            $table->integer('record_Guardianes')->default(0);
            $table->integer('record_Cazador')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('record_games_children');
    }
};
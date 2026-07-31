<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('simulation_challenge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('simulation_attempt')->default(1);
            $table->json('simulation_answers')->nullable();
            $table->integer('simulation_current_step')->default(1);
            $table->integer('simulation_is_completed')->default(0); // 0 = En progreso, 1 = Completado
            $table->string('simulation_empathy_level')->nullable(); // Para guardar el resultado final
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_challenge');
    }
};
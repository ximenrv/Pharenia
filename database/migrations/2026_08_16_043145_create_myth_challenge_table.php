<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('myth_challenge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('myth_attempt')->default(1);
            $table->json('myth_answers')->nullable();
            $table->integer('myth_current_step')->default(1);
            $table->integer('myth_is_completed')->default(0); 
            $table->string('myth_result')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('myth_challenge');
    }
};
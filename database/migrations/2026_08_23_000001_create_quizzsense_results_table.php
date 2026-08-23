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
        Schema::create('quizzsense_results', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');

            $table->date('session_date');
            $table->integer('correct_answers')->default(0);
            $table->integer('total_questions')->default(0);
            $table->json('category_summary')->nullable();

            $table->timestamps();

            // Un usuario solo puede tener un resultado oficial por día.
            $table->unique(['email', 'session_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzsense_results');
    }
};

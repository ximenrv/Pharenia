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
        Schema::create('centinela_results', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');

            $table->string('difficulty');
            $table->date('session_date');
            $table->integer('score')->default(0);
            $table->integer('precision')->default(0);
            $table->integer('protected_count')->default(0);
            $table->integer('threats')->default(0);
            $table->integer('integrity_remaining')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centinela_results');
    }
};

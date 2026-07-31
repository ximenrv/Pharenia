<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mchat_challenge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('mchat_attempt')->default(1);
            $table->json('mchat_answers')->nullable();
            $table->integer('mchat_current_step')->default(1);
            $table->boolean('mchat_is_completed')->default(false);
            $table->integer('mchat_total_score')->nullable();
            $table->string('mchat_risk_level')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mchat_challenge');
    }
};
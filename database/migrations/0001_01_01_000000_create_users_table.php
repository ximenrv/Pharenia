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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre completo
            $table->string('email')->unique();
            $table->date('birthdate');
            $table->string('role')->default('ally_no_tea'); // tutor, adult_tea, ally_no_tea, teen, minor
            $table->string('password');
            // Columna para guardar el ID del adulto supervisor (opcional / nullable)
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
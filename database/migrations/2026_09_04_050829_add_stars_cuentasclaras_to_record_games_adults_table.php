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
        Schema::table('record_games_adults', function (Blueprint $table) {
            $table->integer('stars_CuentasClaras')->default(0)->after('stars_SigueLaReceta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('record_games_adults', function (Blueprint $table) {
            $table->dropColumn('stars_CuentasClaras');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('game_actions', function (Blueprint $table) {
            $table->integer('seconds')->nullable()->after('period'); // Agrega la columna después de 'period'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_actions', function (Blueprint $table) {
            //
        });
    }
};

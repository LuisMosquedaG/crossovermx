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
        Schema::table('games', function (Blueprint $table) {
            // Modificamos la columna 'status' para añadir 'playing' a la lista de valores permitidos
            $table->enum('status', ['pending', 'playing', 'finished'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            // Aquí quitamos 'playing' si alguna vez necesitamos revertir la migración
            $table->enum('status', ['pending', 'finished'])->change();
        });
    }
};

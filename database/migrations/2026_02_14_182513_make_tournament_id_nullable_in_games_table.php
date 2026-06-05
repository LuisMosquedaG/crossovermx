<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Cambiamos la columna para que acepte NULL
            $table->foreignId('tournament_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Si queremos revertir, volvemos a hacerlo obligatorio
            $table->foreignId('tournament_id')->nullable(false)->change();
        });
    }
};

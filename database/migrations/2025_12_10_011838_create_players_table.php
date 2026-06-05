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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del jugador
            $table->string('number')->nullable(); // Número de la camiseta
            $table->string('position')->nullable(); // Posición (Base, Escolta, Alero, etc.)
            $table->foreignId('team_id') // Clave foránea para el equipo
                ->constrained('teams')
                ->onDelete('cascade'); // Si se borra un equipo, se borran sus jugadores
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

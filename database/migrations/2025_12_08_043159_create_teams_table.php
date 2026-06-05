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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del equipo
            $table->string('logo')->nullable(); // Ruta a la imagen del logo
            $table->string('coach_name')->nullable(); // Nombre del entrenador
            $table->foreignId('tournament_id') // Esta es la clave foránea
                ->constrained('tournaments') // Conecta con la tabla 'tournaments'
                ->onDelete('cascade'); // Si se borra un torneo, se borran sus equipos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

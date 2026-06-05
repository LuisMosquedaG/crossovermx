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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del torneo
            $table->text('description')->nullable(); // Descripción o reglas
            $table->date('start_date'); // Fecha de inicio
            $table->date('end_date')->nullable(); // Fecha de fin
            $table->string('location')->nullable(); // Ubicación principal
            $table->boolean('is_active')->default(true); // Para saber si el torneo está activo
            $table->timestamps(); // Crea 'created_at' y 'updated_at' automáticamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};

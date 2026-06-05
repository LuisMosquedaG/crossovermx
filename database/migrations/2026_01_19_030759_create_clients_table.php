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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del cliente/organización
            $table->string('contact_name')->nullable(); // Nombre de la persona de contacto
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo_path')->nullable(); // Ruta de la imagen del logo
            $table->timestamps();
            $table->softDeletes(); // Para poder borrar suavemente si es necesario
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
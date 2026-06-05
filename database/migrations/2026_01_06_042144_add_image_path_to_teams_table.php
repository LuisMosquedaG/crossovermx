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
        Schema::table('teams', function (Blueprint $table) {
            // Agregamos la columna image_path.
            // ->nullable() es importante porque los equipos existentes no tienen foto aún
            // ->after('name') (opcional) pone la columna justo después del nombre
            $table->string('image_path')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            // Esto sirve para si luego quieres revertir la migración (rollback)
            $table->dropColumn('image_path');
        });
    }
};
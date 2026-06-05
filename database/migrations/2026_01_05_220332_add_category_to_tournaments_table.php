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
        Schema::table('tournaments', function (Blueprint $table) {
            // EL CAMBIO VA AQUÍ DENTRO
            $table->string('category')->default('varonil')->after('location'); 
            // Nota: He agregado ->after('location') para que quede ordenado en la base de datos, pero es opcional.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            // Para poder revertir (rollback) la migración, debemos eliminar la columna
            $table->dropColumn('category');
        });
    }
};
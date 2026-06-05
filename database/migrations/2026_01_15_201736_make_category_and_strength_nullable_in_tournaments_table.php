<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('tournaments', function (Blueprint $table) {
        // Cambiamos las columnas para que permitan nulos (nullable)
        $table->string('category')->nullable()->change();
        $table->string('fuerza')->nullable()->change();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            //
        });
    }
};

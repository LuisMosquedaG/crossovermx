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
        // Verificar si la tabla no existe antes de crearla
        if (!Schema::hasTable('courts')) {
            Schema::create('courts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('location')->nullable();
                $table->integer('capacity')->nullable();
                $table->string('surface_type')->nullable();
                $table->string('image_path')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
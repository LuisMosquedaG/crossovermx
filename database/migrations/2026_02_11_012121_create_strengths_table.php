<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('strengths', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Ej: "1ra Fuerza", "Infantil", "Libre"
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade'); // Multi-tenant
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('strengths');
}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('game_comments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('game_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quién escribió el comentario
        $table->foreignId('player_id')->nullable()->constrained()->onDelete('cascade'); // Si es para un jugador específico, si es null es general
        $table->text('content')->comment('Contenido del comentario');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('game_comments');
}
};

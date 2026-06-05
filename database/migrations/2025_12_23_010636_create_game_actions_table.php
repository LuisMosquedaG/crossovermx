<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('game_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('cascade'); // Puede ser nulo (ej. tiempo fuera)
            
            $table->enum('team_side', ['local', 'away']);
            
            // Tipos de acción que podemos registrar
            $table->enum('action_type', [
                'point_scored', 'foul_personal', 'foul_technical', 
                'foul_unsportsmanlike', 'foul_disqualifying', 'timeout_called'
            ]);
            
            $table->integer('value')->nullable(); // Para guardar los puntos (+1, +2, +3)
            $table->integer('period')->default(1); // En qué periodo ocurrió
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_actions');
    }
};

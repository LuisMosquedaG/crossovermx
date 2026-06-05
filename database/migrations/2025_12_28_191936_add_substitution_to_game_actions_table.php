<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('game_actions', function (Blueprint $table) {
        // Tienes que escribir TODO el listado de opciones nuevamente agregando 'substitution'
        $table->enum('action_type', [
            'point_scored', 
            'foul_personal', 
            'foul_technical', 
            'foul_unsportsmanlike', 
            'foul_disqualifying', 
            'timeout_called',
            'substitution' // <--- Esta es la nueva opción
        ])->change();
    });
}

public function down()
{
    Schema::table('game_actions', function (Blueprint $table) {
        // Para revertir, quitamos 'substitution'
        $table->enum('action_type', [
            'point_scored', 
            'foul_personal', 
            'foul_technical', 
            'foul_unsportsmanlike', 
            'foul_disqualifying', 
            'timeout_called'
        ])->change();
    });
}
};

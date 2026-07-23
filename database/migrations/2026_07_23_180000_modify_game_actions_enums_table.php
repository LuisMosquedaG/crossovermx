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
        Schema::table('game_actions', function (Blueprint $table) {
            $table->enum('team_side', ['local', 'away', 'system'])->change();
            $table->enum('action_type', [
                'point_scored', 
                'foul_personal', 
                'foul_technical', 
                'foul_unsportsmanlike', 
                'foul_disqualifying', 
                'timeout_called',
                'substitution',
                'overtime_started',
                'compensation_added'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_actions', function (Blueprint $table) {
            $table->enum('team_side', ['local', 'away'])->change();
            $table->enum('action_type', [
                'point_scored', 
                'foul_personal', 
                'foul_technical', 
                'foul_unsportsmanlike', 
                'foul_disqualifying', 
                'timeout_called',
                'substitution'
            ])->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('game_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->after('player_id');
            // Opcional: Si deseas integridad referencial
            // $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_comments', function (Blueprint $table) {
            //
        });
    }
};

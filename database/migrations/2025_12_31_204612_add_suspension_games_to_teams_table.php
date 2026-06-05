<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            // Columna para contar partidos de suspensión
            $table->integer('suspension_games')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('suspension_games');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('game_player', function (Blueprint $table) {
        $table->boolean('is_active')->default(0)->after('is_starter');
    });
}

public function down()
{
    Schema::table('game_player', function (Blueprint $table) {
        $table->dropColumn('is_active');
    });
}
};

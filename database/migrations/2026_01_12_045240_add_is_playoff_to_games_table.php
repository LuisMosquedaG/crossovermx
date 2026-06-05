<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('games', function (Blueprint $table) {
        $table->boolean('is_playoff')->default(false)->after('tournament_id');
    });
}

public function down()
{
    Schema::table('games', function (Blueprint $table) {
        $table->dropColumn('is_playoff');
    });
}
};

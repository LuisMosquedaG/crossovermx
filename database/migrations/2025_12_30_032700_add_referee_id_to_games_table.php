<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->foreignId('referee_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropForeign(['referee_id']);
            $table->dropColumn('referee_id');
        });
    }
};
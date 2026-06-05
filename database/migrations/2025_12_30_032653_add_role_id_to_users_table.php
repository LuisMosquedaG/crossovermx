<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Añadimos el campo rol al usuario. 
            // Por defecto, lo dejamos nulo o podrías establecer 1 si es el Admin principal
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};  
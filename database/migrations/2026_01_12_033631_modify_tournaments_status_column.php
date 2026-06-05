<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('tournaments', function (Blueprint $table) {
        // Eliminar la columna antigua
        $table->dropColumn('is_active');
        
        // Agregar la nueva columna de estado
        $table->string('status')->default('pending')->after('location');
    });
}

public function down()
{
    Schema::table('tournaments', function (Blueprint $table) {
        $table->dropColumn('status');
        $table->boolean('is_active')->default(false)->after('location');
    });
}
};

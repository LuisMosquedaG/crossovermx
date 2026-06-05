<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            // Es VITAL agregar ->nullable() porque tus datos actuales permiten nulos
            $table->text('rfc')->nullable()->change();
            $table->text('curp')->nullable()->change();
            $table->text('blood_type')->nullable()->change();
            $table->text('emergency_contact_name')->nullable()->change();
            $table->text('emergency_contact_relationship')->nullable()->change();
            $table->text('emergency_contact_address')->nullable()->change();
            $table->text('emergency_contact_phone')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            // En caso de querer revertir, volvemos a varchar (con longitud segura)
            $table->string('rfc', 255)->change();
            $table->string('curp', 18)->change();
            $table->string('blood_type', 5)->change();
            $table->string('emergency_contact_name', 255)->change();
            $table->string('emergency_contact_relationship', 255)->change();
            $table->string('emergency_contact_address', 255)->change();
            $table->string('emergency_contact_phone', 20)->change();
        });
    }
};

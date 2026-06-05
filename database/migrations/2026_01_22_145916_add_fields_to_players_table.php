<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('players', function (Blueprint $table) {
        $table->string('curp', 18)->nullable()->after('rfc');
        
        // Datos de contacto de emergencia
        $table->string('blood_type', 5)->nullable()->after('curp'); // Ej: O+, A-
        $table->string('emergency_contact_name')->nullable();
        $table->string('emergency_contact_relationship')->nullable();
        $table->string('emergency_contact_address')->nullable();
        $table->string('emergency_contact_phone')->nullable();
    });
}

public function down()
{
    Schema::table('players', function (Blueprint $table) {
        $table->dropColumn([
            'curp',
            'blood_type',
            'emergency_contact_name',
            'emergency_contact_relationship',
            'emergency_contact_address',
            'emergency_contact_phone'
        ]);
    });
}
};

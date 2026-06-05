<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('court_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained()->onDelete('cascade');
            $table->integer('day_of_week'); // 0 = Domingo, 1 = Lunes, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_schedules');
    }
};

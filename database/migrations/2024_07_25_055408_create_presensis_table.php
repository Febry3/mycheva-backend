<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id('id_presensi');
            $table->bigInteger('id_jadwal')->unsigned();
            $table->bigInteger('id_user')->unsigned();
            $table->string('status')->default(0);
            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwals');
            $table->foreign('id_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('pegawai_id')->constrained('master_pegawai');
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            // $table->time('jam_keluar')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->timestamps();

            // $table->foreign('pegawai_id')->references('id')->on('master_pegawai');
            $table->foreign('pegawai_id')->references('id')->on('master_pegawai')->onDelete('cascade');
        });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensi');
    }
}

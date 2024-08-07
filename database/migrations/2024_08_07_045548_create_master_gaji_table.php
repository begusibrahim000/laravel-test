<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterGajiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_gaji', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('pegawai_id')->constrained('master_pegawai');
            $table->unsignedBigInteger('pegawai_id');
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('denda_keterlambatan', 15, 2);
            $table->decimal('potongan_hari', 15, 2);
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
        Schema::dropIfExists('master_gaji');
    }
}

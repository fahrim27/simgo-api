<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusPegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_pegawais', function (Blueprint $table) {
            $table->string('nomor_lokasi', 20);
            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('pangkat_gol');
            $table->string('unit_kerja');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_pegawais');
    }
}

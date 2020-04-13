<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusRuangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_ruangans', function (Blueprint $table) {
            $table->string('nomor_lokasi', 20);
            $table->string('kode_ruangan', 25)->unique();
            $table->string('nama_ruangan', 100);
            $table->string('nip_peng_ruangan', 22);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_ruangans');
    }
}

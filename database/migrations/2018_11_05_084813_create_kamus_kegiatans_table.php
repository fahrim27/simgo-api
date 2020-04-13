<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_kegiatans', function (Blueprint $table) {
            $table->string('id_kegiatan', 70)->unique();
            $table->string('kode_kegiatan', 50);
            $table->string('nama_kegiatan', 150);
            $table->string('nomor_sub_unit', 16);
            $table->integer('tahun', 4);
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
        Schema::dropIfExists('kamus_kegiatans');
    }
}

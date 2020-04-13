<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeNomorLokasiSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rincian_masuks', function (Blueprint $table) {
            $table->string('nomor_lokasi', 30)->change();
            $table->string('id_aset', 75)->change();
            $table->string('no_register', 75)->change();
            $table->string('bidang_barang', 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rincian_masuks', function (Blueprint $table) {
            $table->string('nomor_lokasi')->change();
            $table->string('id_aset')->change();
            $table->string('no_register')->change();
            $table->string('bidang_barang')->change();
        });
    }
}

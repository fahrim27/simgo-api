<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusLokasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_lokasis', function (Blueprint $table) {
            $table->string('nomor_sub_unit', 16);
            $table->string('nomor_lokasi', 20)->unique();
            $table->string('nama_lokasi', 100);
            $table->string('nip_kepala', 22);
            $table->string('nip_bendahara_barang', 22);
            $table->string('alamat', 100);
            $table->decimal('telepon', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_lokasis');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRincianKoreksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rincian_koreksis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomor_lokasi', 30);
            $table->string('kode_koreksi', 3);
            $table->string('kode_sb_koreksi', 2);
            $table->string('no_key');
            $table->string('bidang_barang', 2)->nullable();
            $table->string('id_aset', 100);
            $table->string('no_ba_penerimaan')->nullable();
            $table->string('no_register',100);
            $table->string('no_label')->nullable();
            $table->string('nama_barang', 200)->nullable();
            $table->string('merk_alamat', 200)->nullable();
            $table->string('kode_108', 100)->nullable();
            $table->string('kode_108_baru', 100)->nullable();
            $table->string('kode_64', 50)->nullable();
            $table->string('kode_64_baru', 50)->nullable();
            $table->string('kode_kepemilikan', 2)->nullable();
            $table->string('kode_kepemilikan_baru', 2)->nullable();
            $table->integer('jumlah_barang')->nullable();
            $table->integer('jumlah_barang_baru')->nullable();
            $table->decimal('nilai', 15, 2)->nullable();
            $table->decimal('nilai_baru', 15, 2)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->decimal('tahun_koreksi', 4, 0);
            $table->string('no_register_baru', 100)->nullable();
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
        Schema::dropIfExists('rincian_koreksis');
    }
}

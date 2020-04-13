<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRincianKeluarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rincian_keluars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomor_lokasi', 30);
            $table->string('no_key');
            $table->string('bidang_barang',2)->nullable();
            $table->string('id_aset', 100);
            $table->string('no_ba_penerimaan')->nullable();
            $table->string('no_register');
            $table->string('no_label')->nullable();
            $table->string('kode_kepemilikan', 2)->nullable();
            $table->string('nama_barang', 200)->nullable();
            $table->string('merk_alamat', 200)->nullable();
            $table->string('kode_108', 100)->nullable();
            $table->string('kode_64', 50)->nullable();
            $table->string('nopol', 50)->nullable();
            $table->integer('jumlah_barang');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('nilai_keluar', 15, 2);
            $table->integer('jumlah_kembali')->nullable();
            $table->char('pajak', 1)->nullable();
            $table->string('huruf', 100)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->string('kondisi', 50)->nullable();
            $table->string('penguasaan', 50)->nullable();
            $table->decimal('tahun_spj', 4, 0);
            $table->string('kode_jurnal', 3);
            $table->integer('baik')->nullable();
            $table->integer('kb')->nullable();
            $table->integer('rb')->nullable();
            $table->integer('sendiri')->nullable();
            $table->integer('sengketa')->nullable();
            $table->integer('pihak_3')->nullable();
            $table->char('diterima', 1)->nullable();
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
        Schema::dropIfExists('rincian_keluars');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurnalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('jurnals', function (Blueprint $table) {
            $table->string('no_key')->unique();
            $table->char('terkunci', 1);
            $table->string('kode_jurnal')->nullable();
            $table->string('kode_pencatat')->nullable();
            $table->string('nomor_lokasi', 20);
            $table->string('no_ba_penerimaan');
            $table->date('tgl_ba_penerimaan')->nullable();
            $table->string('instansi_yg_menyerahkan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('instansi_tujuan')->nullable();
            $table->date('tgl_update')->nullable();
            $table->string('operator')->nullable();
            $table->decimal('tahun_spj', 4, 0);
            $table->string('keterangan')->nullable();
            $table->integer('nomor')->nullable();
            $table->string('no_ba_st')->nullable();
            $table->date('tgl_ba_st')->nullable();
            $table->string('nip_pengeluar')->nullable();
            $table->string('nama_pengeluar')->nullable();
            $table->string('jabatan_pengeluar')->nullable();
            $table->string('pangkat_gol_pengeluar')->nullable();
            $table->string('instansi_penerima')->nullable();
            $table->string('nip_penerima')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('jabatan_penerima')->nullable();
            $table->string('pangkat_gol_penerima')->nullable();
            $table->string('atasan_pengeluar')->nullable();
            $table->string('nip_atasan_pengeluar')->nullable();
            $table->string('jabatan_atasan_pengeluar')->nullable();
            $table->string('pangkat_gol_atasan_pengeluar')->nullable();
            $table->string('jenis_dokumen')->nullable();
            $table->string('no_dokumen')->nullable();
            $table->date('tgl_dokumen')->nullable();
            $table->char('tutup_buku', 1)->nullable();
            $table->char('cek_pilih', 1)->nullable();
            $table->char('eksport', 1)->nullable();
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
        Schema::dropIfExists('jurnals');
    }
}

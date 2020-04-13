<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('no_key')->unique();
            $table->char('terkunci', 1);
            $table->string('kode_jurnal')->nullable();
            $table->string('kode_pencatat')->nullable();
            $table->string('kode_kegiatan')->nullable();
            $table->string('nomor_lokasi', 20);
            $table->string('dari_lokasi')->nullable();
            $table->string('bdp')->nullable();
            $table->decimal('tahun_bdp', 4, 0)->nullable();
            $table->char('otomatis', 1)->nullable();
            $table->string('no_sa')->nullable();
            $table->string('no_ba_penerimaan');
            $table->date('tgl_ba_penerimaan')->nullable();
            $table->string('instansi_yg_menyerahkan')->nullable();
            $table->string('alamat')->nullable();
            $table->date('tgl_update')->nullable();
            $table->string('operator')->nullable();
            $table->decimal('tahun_spj', 4, 0);
            $table->string('nomor_sub_unit', 20)->nullable();
            $table->decimal('tahun_spm', 4, 0)->nullable();
            $table->string('keterangan')->nullable();
            $table->decimal('nilai_spk', 15, 2)->nullable();
            $table->string('uraian_spk')->nullable();
            $table->string('no_sp2d')->nullable();
            $table->date('tgl_sp2d')->nullable();
            $table->integer('nomor')->nullable();
            $table->string('no_spk_sp_dokumen')->nullable();
            $table->string('tgl_spk_sp_dokumen')->nullable();
            $table->string('no_spm')->nullable();
            $table->date('tgl_spm')->nullable();
            $table->string('no_ba_st')->nullable();
            $table->date('tgl_ba_st')->nullable();
            $table->string('no_bap')->nullable();
            $table->date('tgl_bap')->nullable();
            $table->string('id_kontrak')->nullable();
            $table->char('tutup_buku', 1)->nullable();
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
        Schema::dropIfExists('transaksis');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurnalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_details', function (Blueprint $table) {
            $table->string('no_key');
            $table->string('id_jurnal_detail')->unique();
            $table->integer('no_urut')->nullable();
            $table->string('kode_jurnal');
            $table->string('pos_entri')->nullable();
            $table->string('bidang_barang')->nullable();
            $table->string('id_aset');
            $table->string('no_ba_penerimaan')->nullable();
            $table->string('no_register')->nullable();
            $table->string('no_label')->nullable();
            $table->string('kode_kepemilikan')->nullable();
            $table->integer('jumlah_barang')->nullable();
            $table->decimal('nilai_masuk_keluar', 15, 2)->nullable();
            $table->integer('jumlah_kembali')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('huruf')->nullable();
            $table->string('kondisi')->nullable();
            $table->string('penguasaan')->nullable();
            $table->string('posting')->nullable();
            $table->integer('baik')->nullable();
            $table->integer('kb')->nullable();
            $table->integer('rb')->nullable();
            $table->integer('sendiri')->nullable();
            $table->integer('sengketa')->nullable();
            $table->integer('pihak_3')->nullable();
            $table->string('kode_koreksi')->nullable();
            $table->string('sebelum_koreksi')->nullable();
            $table->string('sesudah_koreksi')->nullable();
            $table->string('kondisi_lama')->nullable();
            $table->string('new_baik')->nullable();
            $table->string('new_kb')->nullable();
            $table->string('new_rb')->nullable();
            $table->decimal('tahun_koreksi', 4, 0);
            $table->string('kd_sebab')->nullable();
            $table->string('id_aset_baru')->nullable();
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
        Schema::dropIfExists('jurnal_details');
    }
}

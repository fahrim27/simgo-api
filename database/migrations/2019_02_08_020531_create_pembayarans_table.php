<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomor_sub_unit');
            $table->string('no_jurnal')->nullable();
            $table->string('no_ba_st')->nullable();
            $table->string('kode_rek_belanja');
            $table->integer('termin_ke')->nullable();
            $table->string('uraian_belanja')->nullable();
            $table->string('no_spm_spmu');
            $table->date('tgl_spm_spmu')->nullable();
            $table->decimal('nilai_spm_spmu', 15, 2)->nullable();
            $table->decimal('tahun_spj', 4, 0);
            $table->string('operator')->nullable();
            $table->date('tgl_update')->nullable();
            $table->string('no_sp2d');
            $table->date('tgl_sp2d')->nullable();
            $table->string('no_sko')->nullable();
            $table->date('tgl_sko')->nullable();
            $table->string('no_spp')->nullable();
            $table->date('tgl_spp')->nullable();
            $table->string('keterangan')->nullable();
            $table->decimal('tahun_jurnal', 4, 0)->nullable();
            $table->string('no_key')->nullable();
            $table->string('id_spm')->nullable();
            $table->timestamps('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
}

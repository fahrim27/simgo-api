<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusSpksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_spks', function (Blueprint $table) {
            $table->string('id_kontrak', 120)->unique();
            $table->string('id_kegiatan', 70)->nullable();
            $table->string('nomor_sub_unit', 16)->nullable();
            $table->string('no_spk_sp_dokumen', 100)->nullable();
            $table->date('tgl_spk_sp_dokumen')->nullable();
            $table->decimal('nlai_spk', 15, 2);
            $table->decimal('tahun_spj', 4, 0)->nullable();
            $table->string('rekanan', 50)->nullable();
            $table->string('alamat_rekanan', 100)->nullable();
            $table->string('termin', 50)->nullable();
            $table->integer('estimasi_termin')->nullable();
            $table->char('addendum', 1)->nullable();
            $table->string('no_add', 50)->nullable();
            $table->date('tgl_add')->nullable();
            $table->string('uraian_add', 150)->nullable();
            $table->decimal('nilai_add', 15, 2);
            $table->decimal('tahun_add', 4, 0)->nullable();
            $table->integer('jml_termin_add')->nullable();
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
        Schema::dropIfExists('kamus_spks');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServeralColumnsTo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurnals', function (Blueprint $table) {
            $table->string('dari_lokasi')->nullable()->change();
            $table->string('kode_kegiatan')->nullable()->change();
            $table->string('nama_kegiatan')->nullable()->change();
            $table->string('id_kontrak')->nullable()->change();
            $table->string('no_spk_sp_dokumen')->after('nama_kegiatan')->nullable();
            $table->date('tgl_spk_sp_dokumen')->after('no_spk_sp_dokumen')->nullable();
            $table->decimal('nilai_spk', 15, 2)->after('tgl_spk_sp_dokumen')->nullable();
            $table->string('uraian_spk')->after('nilai_spk')->nullable();
            $table->decimal('tahun_spm', 4, 0)->after('uraian_spk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurnals', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeskripsiSpkDokumenToKamusSpks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamus_spks', function (Blueprint $table) {
            $table->string('deskripsi_spk_dokumen')->after('tgl_spk_sp_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamus_spks', function (Blueprint $table) {
            //
        });
    }
}

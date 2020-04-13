<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameKodeRuanganInKamusRuangans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamus_ruangans', function (Blueprint $table) {
            $table->renameColumn('kode_ruangan', 'nomor_ruangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamus_ruangans', function (Blueprint $table) {
            $table->renameColumn('nomor_ruangan', 'kode_ruangan');
        });
    }
}

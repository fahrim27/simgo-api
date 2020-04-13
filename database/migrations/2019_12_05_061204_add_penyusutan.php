<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPenyusutan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rincian_keluars', function (Blueprint $table) {
            $table->decimal('penyusutan_per_tahun', 15, 2)->after('nilai_keluar')->nullable();
            $table->decimal('akumulasi_penyusutan', 15, 2)->after('penyusutan_per_tahun')->nullable();
            $table->integer('waktu_susut')->after('akumulasi_penyusutan')->nullable();
            $table->decimal('tahun_buku', 4, 0)->after('waktu_susut')->nullable();
            $table->decimal('nilai_buku', 15, 2)->after('tahun_buku')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rincian_keluars', function (Blueprint $table) {
            //
        });
    }
}

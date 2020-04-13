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
            $table->string('dari_lokasi', 20)->after('nomor_lokasi');
            $table->string('kode_kegiatan', 50)->after('ke_lokasi');
            $table->string('nama_kegiatan', 150)->after('kode_kegiatan');
            $table->string('id_kontrak', 120)->after('nama_kegiatan');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUraianKelompokToKamusRekenings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamus_rekenings', function (Blueprint $table) {
            $table->string('uraian_kelompok')->after('kelompok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamus_rekenings', function (Blueprint $table) {
            //
        });
    }
}

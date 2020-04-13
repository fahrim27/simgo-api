<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubRincianObjekToKamusPermen108sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamus_permen_108s', function (Blueprint $table) {
            $table->string('sub_rincian_objek')->after('rincian_objek');
            $table->string('sub_sub_rincian_objek')->after('sub_rincian_objek');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamus_permen_108s', function (Blueprint $table) {
            //
        });
    }
}

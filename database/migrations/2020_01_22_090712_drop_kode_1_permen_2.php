<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropKode1Permen2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamus_mapping_permens', function (Blueprint $table) {
            $table->dropColumn('kode_1_permen_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamus_mapping_permens', function (Blueprint $table) {
            $table->string('kode_1_permen_2');
        });
    }
}

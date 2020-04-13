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
        Schema::table('kamus_rekenings', function (Blueprint $table) {
            $table->string('rek_64')->after('uraian')->nullable();
            $table->string('urain_rek_64')->after('rek_64')->nullable();
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

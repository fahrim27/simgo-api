<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusPermen17sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_permen_17s', function (Blueprint $table) {
            $table->string('golongan');
            $table->string('bidang');
            $table->string('kelompok');
            $table->string('sub_kelompok');
            $table->string('sub_sub_kelompok');
            $table->string('kode')->unique();
            $table->string('uraian');
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
        Schema::dropIfExists('kamus_permen_17s');
    }
}

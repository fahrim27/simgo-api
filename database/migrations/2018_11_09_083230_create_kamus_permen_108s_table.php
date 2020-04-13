<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusPermen108sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_permen_108s', function (Blueprint $table) {
            $table->string('akun');
            $table->string('kelompok');
            $table->string('jenis');
            $table->string('objek');
            $table->string('rincian_objek');
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
        Schema::dropIfExists('kamus_permen_108s');
    }
}

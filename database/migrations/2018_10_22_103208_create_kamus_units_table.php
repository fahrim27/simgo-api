<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_units', function (Blueprint $table) {
            $table->string('nomor_bidang_unit', 8);
            $table->string('nomor_unit', 11)->unique();
            $table->string('nama_unit', 100);
            $table->string('nip_kepala_unit', 22);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_units');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusSubUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_sub_units', function (Blueprint $table) {
            $table->string('nomor_unit', 11);
            $table->string('nomor_sub_unit', 16)->unique();
            $table->string('nama_sub_unit', 100);
            $table->string('nip_pimpinan', 22);
            $table->decimal('pemegang_anggaran', 1, 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_sub_units');
    }
}

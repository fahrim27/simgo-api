<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusBidangUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_bidang_units', function (Blueprint $table) {
            $table->string('nomor_kab', 5);
            $table->string('nomor_bidang_unit', 8)->unique();
            $table->string('nama_bidang_unit', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kamus_bidang_units');
    }
}

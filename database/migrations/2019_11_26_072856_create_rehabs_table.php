<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rehabs', function (Blueprint $table) {
            $table->string('rehab_id', 50)->unique();
            $table->string('nama_rehab', 200);
            $table->decimal('tahun_rehab', 4, 0);
            $table->decimal('nilai_rehab', 15, 2);
            $table->string('kode_rek_rehab', 30);
            $table->string('nomor_lokasi', 30);
            $table->integer('aset_induk_id', 50);
            $table->string('nama_induk', 200);
            $table->decimal('tahun_induk', 4, 0);
            $table->decimal('nilai_induk', 15, 2);
            $table->string('kode_rek_induk', 30);
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
        Schema::dropIfExists('rehabs');
    }
}

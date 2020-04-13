<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMappingLokasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapping_lokasis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nomor_lokasi_17', 30);
            $table->string('nomor_lokasi_108', 30);
            $table->string('nama_lokasi', 30);
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
        Schema::dropIfExists('mapping_lokasis');
    }
}

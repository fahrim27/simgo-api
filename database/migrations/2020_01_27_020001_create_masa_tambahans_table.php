<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasaTambahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('masa_tambahans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kelompok')->nullable();
            $table->string('uraian')->nullable();
            $table->string('kode_108')->nullable();
            $table->string('kode_64')->nullable();
            $table->decimal('min',3,0)->nullable();
            $table->decimal('max',3,0)->nullable();
            $table->decimal('masa_tambahan',3,0)->nullable();
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
        Schema::dropIfExists('masa_tambahans');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusMappingPermensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_mapping_permens', function (Blueprint $table) {
            $table->string('permen_1');
            $table->string('kode_1');
            $table->string('uraian_1');
            $table->string('permen_2');
            $table->string('kode_2');
            $table->string('uraian_2');
            $table->string('kode_1_permen_2')->unique();
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
        Schema::dropIfExists('kamus_mapping_permens');
    }
}

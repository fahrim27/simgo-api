<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKamusSpkTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamus_spk_temps', function (Blueprint $table) {
            $table->string('no_kontrak', 70)->nullable();
            $table->string('uraian_kontrak', 16)->nullable();
            $table->decimal('nilai_spk', 15, 2);
            $table->decimal('tahun_spj', 4, 0)->nullable();
            $table->string('rekanan', 50)->nullable();
            $table->string('alamat_rekanan', 100)->nullable();
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
        Schema::dropIfExists('kamus_spk_temps');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenambahanNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penambahan_nilais', function (Blueprint $table) {
            $table->string('pnid')->unique();
            $table->string('namapn')->nullable();
            $table->decimal('tahunpn', 4, 0)->nullable();
            $table->decimal('nilaipn', 15, 0)->nullable();
            $table->string('lokasipn')->nullable();
            $table->string('subunitpn')->nullable();
            $table->string('asetindukid');
            $table->string('kodepn')->nullable();
            $table->string('bidangpn')->nullable();
            $table->string('namainduk')->nullable();
            $table->decimal('tahuninduk', 4, 0)->nullable();
            $table->string('kodeinduk')->nullable();
            $table->string('bidanginduk')->nullable();
            $table->decimal('tambah_masa_manfaat', 1, 0)->nullable();
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
        Schema::dropIfExists('penambahan_nilais');
    }
}

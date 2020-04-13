<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenunjangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penunjangs', function (Blueprint $table) {
            $table->string('no_spk_penunjang')->unique();
            $table->string('no_spm_penunjang');
            $table->date('tgl_spm_penunjang');
            $table->date('tgl_spk_sp_dokumen');
            $table->string('id_aset', 100);
            $table->string('no_key', 100);
            $table->decimal('nilai_penunjang', 15, 2);
            $table->decimal('nilai', 15, 2);
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
        Schema::dropIfExists('penunjangs');
    }
}

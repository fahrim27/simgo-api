<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReklas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rincian_masuks', function (Blueprint $table) {
            $table->tinyInteger('reklas')->after('sengketa')->nullable();
            $table->string('kode_asal')->after('reklas')->nullable();
            $table->tinyInteger('aset_rehab')->after('kode_asal')->nullable();
            $table->string('id_aset_induk')->after('aset_rehab')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rincian_masuks', function (Blueprint $table) {
            //
        });
    }
}

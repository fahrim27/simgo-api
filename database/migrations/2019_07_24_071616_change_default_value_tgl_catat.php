<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultValueTglCatat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rincian_masuks', function (Blueprint $table) {
            $table->date('tgl_catat')->nullable()->change();
            $table->decimal('harga_satuan', 15, 2)->change();
            $table->decimal('harga_total', 15, 2)->change();
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
            $table->date('tgl_catat')->change();
            $table->decimal('harga_satuan')->change();
            $table->decimal('harga_total')->change();
        });
    }
}

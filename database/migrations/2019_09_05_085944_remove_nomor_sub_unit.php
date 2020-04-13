<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNomorSubUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamus_lokasis', function (Blueprint $table) {
            $table->dropColumn('nomor_sub_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamus_lokasis', function (Blueprint $table) {
            $table->string('nomor_sub_unit');
        });
    }
}

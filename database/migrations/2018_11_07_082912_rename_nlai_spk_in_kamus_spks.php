<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameNlaiSpkInKamusSpks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kamus_spks', function (Blueprint $table) {
            $table->renameColumn('nlai_spk', 'nilai_spk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kamus_spks', function (Blueprint $table) {
            $table->renameColumn('nilai_spk', 'nlai_spk');
        });
    }
}

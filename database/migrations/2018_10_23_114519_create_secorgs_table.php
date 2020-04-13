<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecorgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secorgs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oparentid');
            $table->string('oname');
            $table->string('onomorunit',20);
            $table->integer('olevel');
            $table->string('opath');
            $table->decimal('oext', 1, 0);
            $table->decimal('oenable', 1, 0);
            $table->string('created_by');
            $table->string('modified_by');
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
        Schema::dropIfExists('secorgs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecmenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secmenus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mparentid');
            $table->string('mname');
            $table->string('mdesc');
            $table->string('muri');
            $table->decimal('mseq', 3, 0);
            $table->decimal('menable', 1, 0);
            $table->decimal('mshow', 1, 0);
            $table->decimal('mpass', 1, 0);
            $table->decimal('mtype', 1, 0);
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
        Schema::dropIfExists('secmenus');
    }
}

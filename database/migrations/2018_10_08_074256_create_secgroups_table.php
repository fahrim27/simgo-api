<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secgroups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('oid');
            $table->string('gname');
            $table->decimal('genable', 1, 0);
            $table->string('createdby');
            $table->string('modifiedby');
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
        Schema::dropIfExists('secgroups');
    }
}

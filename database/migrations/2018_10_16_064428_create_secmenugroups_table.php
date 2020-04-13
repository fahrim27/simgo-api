<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecmenugroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secmenugroups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('secgroups');
            $table->unsignedInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('secmenus');
            $table->decimal('mgcreate', 1, 0);
            $table->decimal('mgread', 1, 0);
            $table->decimal('mgupdate', 1, 0);
            $table->decimal('mgdelete', 1, 0);
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
        Schema::dropIfExists('secmenugroups');
    }
}

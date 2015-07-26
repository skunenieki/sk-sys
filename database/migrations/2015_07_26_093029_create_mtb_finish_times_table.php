<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtbFinishTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtb_finish_times', function (Blueprint $table) {
            $table->increments('id');
            $table->time('finish');
            $table->boolean('disabled');
            $table->smallInteger('eventYear');
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
        Schema::drop('mtb_finish_times');
    }
}

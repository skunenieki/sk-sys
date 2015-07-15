<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualFinishNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_finish_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('slot');
            $table->smallInteger('number')->unsigned()->nullable()->default(null);
            $table->boolean('manual');
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
        Schema::drop('individual_finish_numbers');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualTurnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_turns', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('number')->unsigned()->nullable()->default(null);
            $table->time('turn');
            $table->smallInteger('slot');
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
        Schema::drop('individual_turns');
    }
}

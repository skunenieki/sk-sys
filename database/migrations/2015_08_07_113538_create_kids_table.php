<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participantId')->unsigned();
            $table->smallInteger('number')->unsigned();
            $table->string('name');
            $table->smallInteger('birthYear');
            $table->string('gender');
            $table->text('comment')->nullable()->default(null);
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
        Schema::drop('kids');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriathlonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triathlon', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participantId')->unsigned();
            $table->smallInteger('number')->unsigned();
            $table->string('name');
            $table->smallInteger('birthYear');
            $table->string('gender');
            $table->time('finish')->nullable()->default(null);
            $table->time('penalty')->nullable()->default(null);
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
        Schema::drop('triathlon');
    }
}

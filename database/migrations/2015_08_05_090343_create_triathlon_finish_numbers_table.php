<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriathlonFinishNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triathlon_finish_numbers', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::drop('triathlon_finish_numbers');
    }}

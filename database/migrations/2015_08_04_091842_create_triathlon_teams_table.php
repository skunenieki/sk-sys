<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriathlonTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triathlon_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('number')->unsigned();
            $table->string('name');

            $table->integer('swimmerParticipantId')->unsigned();
            $table->string('swimmerName');
            $table->smallInteger('swimmerBirthYear');
            $table->string('swimmerGender');

            $table->integer('bikerParticipantId')->unsigned();
            $table->string('bikerName');
            $table->smallInteger('bikerBirthYear');
            $table->string('bikerGender');

            $table->integer('runnerParticipantId')->unsigned();
            $table->string('runnerName');
            $table->smallInteger('runnerBirthYear');
            $table->string('runnerGender');

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
        Schema::drop('triathlon_teams');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participantId')->unsigned();
            $table->smallInteger('number')->unsigned();
            $table->string('name');
            $table->smallInteger('birthYear');
            $table->string('gender');
            $table->string('bikeType')->nullable()->default(null);
            $table->time('start')->nullable()->default(null);
            $table->time('turn')->nullable()->default(null);
            $table->time('finish')->nullable()->default(null);
            $table->time('penalty')->nullable()->default(null);
            $table->text('comment')->nullable()->default(null);
            $table->smallInteger('eventYear');
            $table->string('group')->nullable()->default(null);
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
        Schema::drop('individual');
    }
}

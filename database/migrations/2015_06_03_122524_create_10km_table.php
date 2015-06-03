<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create10kmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('10km', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participantId')->unsigned();
            $table->string('name');
            $table->date('birthYear');
            $table->enum('gender', ['V', 'S']);
            $table->enum('bikeType', ['T', 'S', 'C', 'AK']);
            $table->time('start');
            $table->time('turn');
            $table->time('finish');
            $table->time('penalty');
            $table->text('comment');
            $table->date('eventYear');
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
        Schema::drop('10km');
    }
}

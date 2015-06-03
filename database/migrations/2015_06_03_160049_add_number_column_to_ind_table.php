<?php

use Illuminate\Database\Migrations\Migration;

class AddNumberColumnToIndTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('10km', function ($table) {
            $table->smallInteger('number')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('10km', function ($table) {
            $table->dropColumn(['number']);
        });
    }
}

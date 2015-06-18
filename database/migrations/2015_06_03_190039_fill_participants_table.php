<?php

use Illuminate\Database\Migrations\Migration;

class FillParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert('INSERT IGNORE INTO participants (name, birthYear, gender) SELECT DISTINCT name, birthYear, gender FROM individual ORDER BY name;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}




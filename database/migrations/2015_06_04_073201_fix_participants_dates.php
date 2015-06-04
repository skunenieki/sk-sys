<?php

use Illuminate\Database\Migrations\Migration;

class FixParticipantsDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update('UPDATE participants SET created_at=NOW(), updated_at=NOW();');
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

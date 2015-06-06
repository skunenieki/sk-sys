<?php

use Skunenieki\System\Models\Individual;
use Skunenieki\System\Models\Participant;
use Illuminate\Database\Migrations\Migration;

class Link10kmToParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Individual::where('participantId', 0)->get() as $ind) {
            $participant = Participant::where('name', $ind->name)
                          ->where('birthYear', $ind->birthYear)
                          ->first();

            if (null !== $participant) {
                $ind->participantId = $participant->id;
                $ind->save();
            }
        }
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

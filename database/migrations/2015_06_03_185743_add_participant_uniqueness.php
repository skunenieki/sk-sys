<?php

use Illuminate\Database\Migrations\Migration;

class AddParticipantUniqueness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participants', function ($table) {
            $table->unique(['name', 'birthYear', 'gender']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participants', function ($table) {
            $table->dropUnique('participants_name_birthYear_gender_unique');
        });
    }
}

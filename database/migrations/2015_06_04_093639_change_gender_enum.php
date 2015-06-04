<?php

use Illuminate\Database\Migrations\Migration;

class ChangeGenderEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE 10km MODIFY COLUMN gender ENUM('V', 'S', 'AK') NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE 10km MODIFY COLUMN gender ENUM('V', 'S') NOT NULL;");
    }
}

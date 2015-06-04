<?php

use Illuminate\Database\Migrations\Migration;

class Make10kmColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE 10km MODIFY COLUMN start TIME NULL;");
        DB::statement("ALTER TABLE 10km MODIFY COLUMN turn TIME NULL;");
        DB::statement("ALTER TABLE 10km MODIFY COLUMN finish TIME NULL;");
        DB::statement("ALTER TABLE 10km MODIFY COLUMN penalty TIME NULL;");
        DB::statement("ALTER TABLE 10km MODIFY COLUMN number smallint(5) unsigned NOT NULL AFTER `participantId`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE 10km MODIFY COLUMN start TIME NOT NULL;");
        DB::statement("ALTER TABLE 10km MODIFY COLUMN turn TIME NOT NULL;");
        DB::statement("ALTER TABLE 10km MODIFY COLUMN finish TIME NOT NULL;");
        DB::statement("ALTER TABLE 10km MODIFY COLUMN penalty TIME NOT NULL;");
    }
}

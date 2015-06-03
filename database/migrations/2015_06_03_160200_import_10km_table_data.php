<?php

use Illuminate\Database\Eloquent\Model;
use Skunenieki\System\Models\Individual;
use Illuminate\Database\Migrations\Migration;

class Import10kmTableData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();

        DB::table('10km')->delete();

        $data = unserialize(file_get_contents(__DIR__.'/data/10km.txt'));

        foreach ($data as $item) {
            Individual::create($item);
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

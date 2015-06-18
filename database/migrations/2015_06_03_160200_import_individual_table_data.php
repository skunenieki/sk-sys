<?php

use Illuminate\Database\Eloquent\Model;
use Skunenieki\System\Models\Individual;
use Illuminate\Database\Migrations\Migration;

class ImportIndividualTableData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Model::unguard();

        DB::table('individual')->delete();

        $data = unserialize(file_get_contents(__DIR__.'/data/10km.txt'));

        foreach ($data as $item) {
            $item['birthYear'] = $item['birthYear']->format('Y');
            $item['eventYear'] = $item['eventYear']->format('Y');
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
    }
}

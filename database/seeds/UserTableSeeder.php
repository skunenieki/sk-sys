<?php

use Illuminate\Database\Seeder;
use Skunenieki\System\Models\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'email' => 'a@a.a',
            'password' => bcrypt('a'),
        ]);
    }
}

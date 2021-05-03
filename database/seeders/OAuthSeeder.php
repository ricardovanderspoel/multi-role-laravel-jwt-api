<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'fname' => 'Ricardo',
            'lname' => 'van der Spoel',
            'email' => 'rj.vdspoel@gmail.com',
            'phone' => '31648416075',
            'password' => bcrypt('Atxx573896.'),
        ]);
    }
}

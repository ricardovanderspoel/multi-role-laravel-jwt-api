<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_personal_access_clients')->insert([
            'id' => 1,
        ]);
        DB::table('oauthclients')->insert([
            'id' => 1,
            'name' => 'Laravel Personal Access Client',
            'secret' => 'ps1mZjqpUnGrn4Yhl3Z2swjGOsOq6a9unRJsbIS5',
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
        ]);
        DB::table('oauthclients')->insert([
            'id' => 2,
            'name' => 'Laravel Password Grant Client',
            'secret' => 'xddXpNNgzc30160HmWvDw5dJrHcv1Wa8q1jcasV0',
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
        ]);
    }
}

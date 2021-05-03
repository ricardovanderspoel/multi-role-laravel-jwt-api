<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ProductCategorySeeder::class,
            OAuthSeeder::class
        ]);

        \App\Models\User::factory(100)->create();
        \App\Models\Company::factory(25)->create();
        \App\Models\CompanyUser::factory(100)->create();
        \App\Models\ProductCategory::factory(100)->create();
    }
}

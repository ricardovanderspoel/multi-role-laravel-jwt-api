<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categories')->insert([
            'name' => 'Clothing',
            'parent_id' => null,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Food',
            'parent_id' => null,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Electronics',
            'parent_id' => null,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Shoes',
            'parent_id' => 1,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Jeans',
            'parent_id' => 1,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Shirts',
            'parent_id' => 1,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Jackets',
            'parent_id' => 1,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Meat',
            'parent_id' => 2,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Fish',
            'parent_id' => 2,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Bread',
            'parent_id' => 2,
        ]);
        DB::table('product_categories')->insert([
            'name' => 'Soda',
            'parent_id' => 2,
        ]);
    }
}

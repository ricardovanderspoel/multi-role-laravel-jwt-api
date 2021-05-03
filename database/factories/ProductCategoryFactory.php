<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->word,
            'parent_id' => $this->faker->boolean(90) ? (ProductCategory::all()->random()->first()->id ?? null) : null, //$this->faker->numberBetween(1, 10),
        ];
    }
}

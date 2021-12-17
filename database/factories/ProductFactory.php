<?php

namespace Database\Factories;

use App\Model\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentences(4, true),
            'stock' => $this->faker->numberBetween(0, 100),
            'price' => $this->faker->numberBetween(1, 1000)
        ];
    }
}

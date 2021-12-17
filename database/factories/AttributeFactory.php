<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model\Attribute;

class AttributeFactory extends Factory
{
   

     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attribute::class;

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
        ];
    }
}

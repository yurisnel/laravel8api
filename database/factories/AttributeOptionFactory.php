<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model\AttributeOption;
use App\Model\Attribute;

class AttributeOptionFactory extends Factory
{
    
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AttributeOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'attribute_id' => Attribute::factory()
        ];
    }
}

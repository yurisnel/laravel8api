<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model\ProductAttributeValue;
use App\Model\AttributeOption;
use App\Model\Product;

class ProductAttributeValueFactory extends Factory
{
    
     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductAttributeValue::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'price' => $this->faker->numberBetween(1, 1000),
            'attribute_options_id' => AttributeOption::factory(),
            'product_id' => Product::factory()
        ];
    }
}

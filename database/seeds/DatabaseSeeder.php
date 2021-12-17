<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Model\User;
use App\Model\Product;
use App\Model\Attribute;
use App\Model\AttributeOption;
use App\Model\ProductAttributeValue;

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
            // ProductSeeder::class
        ]);


        User::factory()->count(5)->create();

        $products = Product::factory()->count(5)->create();

        $attributes = [
            "color" => ["blue", "red", "black", "white"],
            "size" => ["small", "medium", "large"],
            "state" => ["new", "repaired", "deteriorated"]
        ];

        foreach ($attributes as $attr => $options) {

            //Create Attribute 
            $attr = Attribute::factory()->count(1)->create([
                'name' => $attr
            ]);

            foreach ($options as $opt) {

                //Create options of attribute
                $attributesOptions = AttributeOption::factory()
                    ->count(1)
                    ->create([
                        'name' => $opt,
                        'attribute_id' => $attr->first()->getKey()
                    ]);

                /*Create product variations by attribute */
                foreach ($products as $p) {
                    foreach ($attributesOptions as $aopt) {
                        if (rand(2, 4) % 2) { // only inserty somes variations
                            continue;
                        }
                        ProductAttributeValue::factory()->create([
                            'product_id' => $p->id,
                            'attribute_options_id' => $aopt->id
                        ]);
                    }
                }
                /*$variations = ProductAttributeValue::factory()
                    ->count(3)
                    ->for($products)
                    ->for($attributesOptions)
                    ->create();*/
            }
        }
    }
}

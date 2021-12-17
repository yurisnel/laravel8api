<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Attribute;
use App\Model\AttributeOption;
use App\Model\ProductAttributeValue;
use  App\Events\ProductUpdateEvent;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'stock', 'price'
    ];

    protected $appends = ['variations'];

    /*creating and created, updating and updated,saving and saved,
     deleting and deleted, restoring and restored, retrieved:*/
    protected $dispatchesEvents = [       
        "saved" => ProductUpdateEvent::class
    ];     



    public function userSubscription()
    {
        return $this->hasMany('App\Model\UserSubscription');
    }

    public function productAttributeValue()
    {
        return $this->hasMany('App\Model\ProductAttributeValue');
    }


    public function getVariationsAttribute()
    {
        //return $this->productAttributeValue()->pluck('id')->flatten();
        //return $this->productAttributeValue()->pluck('id', 'price', 'attribute_options_id');
        return $this->productAttributeValue()->get();
    }

    public static function create($data)
    {
        if (!empty($data["attributes"])) {
            $attributes = $data["attributes"];
            unset($data["attributes"]);
        }
        $model = static::query()->create($data);

        if(!empty($attributes)){
            self::createVariations($model, $attributes);
        }
        return $model;
    }

    public static function createVariations($product, $attributes)
    {
        foreach ($attributes as $attribute_name => $options) {
            $attribute = Attribute::where('name', $attribute_name)->first();
            if (!$attribute) {
                $attribute = Attribute::create(['name' => $attribute_name]);
            }
            foreach ($options as $option_name => $price) {
                $option = AttributeOption::where('name', $option_name)->first();
                if (!$option) {
                    $option = AttributeOption::create([
                        'name' => $option_name,
                        'attribute_id' => $attribute->id
                    ]);
                }

                $data = [
                    'product_id' => $product->id,
                    'attribute_options_id' => $option->id
                ];

                //eliminarlo en caso de que se tratate de una actualización
                ProductAttributeValue::where($data)->delete();

                $data['price'] = $price;
                ProductAttributeValue::create($data);
            }
        }
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProductFactory::new();
    }
}

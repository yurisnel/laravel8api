<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'attribute_options_id', 'price'
    ];

    protected $hidden = ['product_id','attribute_options_id','created_at', 'updated_at'];

    protected $appends = ['option'];

    public function attributeOption()
    {
        return $this->belongsTo('App\Model\AttributeOption', 'attribute_options_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Model\Product');
    }

    public function getOptionAttribute()
    {
        return $this->attributeOption()->first();
    }

    protected static function newFactory()
    {
        return \Database\Factories\ProductAttributeValueFactory::new();
    }
}

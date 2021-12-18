<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    use HasFactory;

    protected $hidden = ['attribute_id', 'created_at', 'updated_at'];
    protected $fillable = ['name', 'attribute_id'];
    protected $appends = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('App\Model\Attribute');
    }

    public function productAttributeValue()
    {
        return $this->hasMany('App\Model\ProductAttributeValue');
    }

    public function getAttributeAttribute()
    {
        return $this->attribute()->first()->name;
    }

    protected static function newFactory()
    {
        return \Database\Factories\AttributeOptionFactory::new();
    }
}

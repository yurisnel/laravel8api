<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    
    protected $hidden = ['created_at', 'updated_at'];

    public function attributeOption()
    {
        return $this->hasMany('App\Model\AttributeOption');
    }

    protected static function newFactory()
    {
        return \Database\Factories\AttributeFactory::new();
    }
}

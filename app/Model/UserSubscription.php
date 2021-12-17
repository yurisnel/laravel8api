<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsTo('App\Model\Product');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $table='product_color';
    public function color()
    {
        return $this->hasOne(Color::class,'id','color_id');
    }
}

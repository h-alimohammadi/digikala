<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table='product_price';

    public function getColor()
    {
        return $this->hasOne(Color::class,'id','color_id');
    }
    public function getProductWarranty()
    {
        return $this->hasOne(ProductWarranty::class,'id','warranty_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatBrand extends Model
{
    protected $table = 'cat_brands';
    protected $fillable = ['cat_id', 'brand_id', 'product_count'];

    public function getBrand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'cat_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'products';
    protected $fillable = ['title', 'ename', 'product_url', 'price', 'discount_price', 'show', 'view',
        'keywords', 'description', 'special', 'cat_id', 'brand_id', 'image_url', 'tozihat', 'order_number', 'status'];

    public static function productStatus()
    {
        $array = [];
        $array[-3] = 'رد شده';
        $array[-2] = 'در انتظار بررسی';
        $array[-1] = 'توقف تولید';
        $array[0] = 'نا موجود';
        $array[1] = 'منتشر شده';
        return $array;
    }

    public static function getData($request)
    {
        $string = '?';
        $product = self::with(['category', 'brand']);
        if (isTrashed($request)) {
            $product = $product->onlyTrashed();
            $string = create_paginate_url($string, 'trashed=true');
        }
//        if (array_key_exists('string', $request) && !empty($request['string'])) {
//            $product = $product->where('name', 'LIKE', '%' . $request['string'] . '%');
//            $product = $product->orWhere('ename', 'LIKE', '%' . $request['string'] . '%');
//            $string = create_paginate_url($string, 'string=' . $request['string']);
//        }
        $product = $product->orderBy('id', 'Desc')->paginate(10);
        $product->withPath($string);
        return $product;
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'cat_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($product){
            if ($product->deleted_at != null){
                if ($product->image_url != null) {
                    remove_file($product->image_url, 'products/');
                    remove_file($product->image_url, 'thumbnails/');
                }
            }
            DB::table('product_color')->where('product_id',$product->id)->delete();
            DB::table('item_value')->where('product_id',$product->id)->delete();
        });
    }

}

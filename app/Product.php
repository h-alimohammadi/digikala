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
        'keywords', 'description', 'special', 'cat_id', 'brand_id', 'image_url', 'tozihat', 'order_number', 'status','use_for_gift_cart','ready_to_shipment'];

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
        return $this->hasOne(Category::class, 'id', 'cat_id')->withDefault(['name' => '']);
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id')
            ->withDefault(['name' => '', 'ename' => '']);
    }

    public function getColor()
    {
        return $this->hasMany(ProductColor::class, 'product_id', 'id');
    }
    public function ProductGallery()
    {
        return $this->hasMany(ProductGallery::class, 'product_id', 'id')->orderBy('position','ASC');
    }

    public function getItemValue()
    {
        return $this->hasMany(ItemValue::class, 'product_id', 'id');
    }

    public function productWarranties()
    {
        return $this->hasMany(ProductWarranty::class, 'product_id', 'id')
            ->where('product_number', '>', 0)
            ->orderBy('price2', 'ASC');
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($product) {
            if ($product->deleted_at != null) {
                if ($product->image_url != null) {
                    remove_file($product->image_url, 'products/');
                    remove_file($product->image_url, 'thumbnails/');
                }
            }
            DB::table('product_color')->where('product_id', $product->id)->delete();
            DB::table('item_value')->where('product_id', $product->id)->delete();
        });
    }

    public function getFirstProductPrice()
    {
        return $this->hasOne(ProductWarranty::class,'product_id','id')
            ->orderBy('price2','ASC')->select(['id','product_id','price1','price2','offers','offers_last_time']);
    }

}

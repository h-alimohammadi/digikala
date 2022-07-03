<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductWarranty extends Model
{
    use SoftDeletes;
    protected $table = 'product_warranties';
    protected $fillable = ['product_id', 'warranty_id', 'color_id', 'price1',
        'price2', 'send_time', 'seller_id', 'product_number', 'product_number_cart'];

    public static function getData($request)
    {
        $string = '?';
        $productWarranty = self::with(['color','warranty'])->orderBy('id', 'desc');
        if (isTrashed($request->all())) {
            $productWarranty = $productWarranty->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
//        if (array_key_exists('string', $request->all())) {
//            $productWarranty = $productWarranty->where('');
//        }
        $productWarranty=$productWarranty->paginate(10);
        $productWarranty->withPath($string);
        return $productWarranty;
    }

    public function color()
    {
        return $this->belongsTo(Color::class,'color_id','id')
            ->withDefault(['name'=>'','id'=>0]);
    }
    public function warranty()
    {
        return $this->belongsTo(Warranty::class,'warranty_id','id');
    }
    protected static function boot()
    {
        parent::boot();
        static::restored(function ($productWarranty){
            add_min_product_price($productWarranty);
            $product = Product::select('id','price','status')->where('id',$productWarranty->product_id)->withTrashed()->first();
            update_product_price($product);
        });
        static::deleted(function ($productWarranty){
            check_has_product_warranty($productWarranty);
            $product = Product::select('id','price','status')->where('id',$productWarranty->product_id)->withTrashed()->first();
            update_product_price($product);
        });
    }
}

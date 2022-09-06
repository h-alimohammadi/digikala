<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    protected $table = 'review_product';
    protected $fillable = ['title', 'tozihat', 'product_id'];
    public static function getData($request)
    {
        $string = '?';
        $review = self::orderBy('id', 'Desc')->whereNotNull('title');
        if (isTrashed($request)) {
            $review = $review->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
        $review = $review->paginate(5);
        $review->withPath($string);
        return $review;
    }
}

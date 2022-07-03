<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use SoftDeletes;
    protected $table = 'sliders';
    protected $fillable = ['title', 'url', 'image_url', 'mobile_image_url'];

    public static function getData($request)
    {
        $string = '?';
        $slider = self::orderBy('id', 'Desc');
        if (isTrashed($request)) {
            $slider = $slider->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('string', $request) && $request['string'] != '') {
            $slider = $slider->where('title', 'LIKE', '%' . $request['string'] . '%');
            create_paginate_url($string, 'string=' . $request['string']);
        }
        $slider = $slider->paginate(5);
        $slider->withPath($string);
        return $slider;
    }
    

    protected static function boot()
    {
        parent::boot();
        self::deleted(function ($slider) {
            if ($slider->isForceDeleting()) {
                remove_file($slider->image_url, 'slider/');
                remove_file($slider->mobile_image_url, 'slider/mobile/');
            }
        });
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    protected $table = 'brands';
    protected $fillable = ['name', 'ename', 'icon', 'description'];

    public static function getData($request)
    {
        $string = '?';
        $brand = self::orderBy('id', 'Desc');
        if (isTrashed($request)) {
            $brand = $brand->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('string', $request) && $request['string'] != '') {
            $brand = $brand->where('name', 'LIKE', '%' . $request['string'] . '%');
            $brand = $brand->orWhere('ename', 'LIKE', '%' . $request['string'] . '%');
            create_paginate_url($string, 'string=' . $request['string']);
        }
        $brand = $brand->paginate(5);
        $brand->withPath($string);
        return $brand;
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function ($brand) {
            if ($brand->isForceDeleting())
                remove_file($brand->icon, 'brand/');
        });
    }

    public function getCat()
    {
        return $this->hasMany(CatBrand::class,'brand_id','id');
    }
}

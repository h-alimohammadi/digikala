<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    protected $table = 'city';
    protected $fillable = ['name', 'province_id', 'send_time', 'send_price', 'min_order_price'];
    public static function getData($request)
    {
        $string = '?';
        $city = self::with('province');
        if (isTrashed($request)) {
            $city = $city->onlyTrashed();
            $string = create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('string', $request) && !empty($request['string'])) {
            $city = $city->where('name', 'LIKE', '%' . $request['string'] . '%');
            $string = create_paginate_url($string, 'string=' . $request['string']);
        }
        $city = $city->orderBy('id', 'Desc')->paginate(10);
        $city->withPath($string);
        return $city;
    }

    public function province()
    {
        return $this->hasOne(Province::class,'id','province_id')->withDefault(['name'=>'']);
    }

}

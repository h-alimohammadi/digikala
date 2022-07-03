<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;
    protected $table = 'colors';
    protected $fillable = ['name', 'code'];

    public static function getData($request)
    {
        $string = '?';
        $color = self::orderBy('id', 'Desc');
        if (isTrashed($request)) {
            $color = $color->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('string', $request) && $request['string'] != '') {
            $color = $color->where('name', 'LIKE', '%' . $request['string'] . '%');
            create_paginate_url($string, 'string=' . $request['string']);
        }
        $color = $color->paginate(5);
        $color->withPath($string);
        return $color;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warranty extends Model
{
    use SoftDeletes;
    protected $table = 'warranties';
    protected $fillable = ['name'];

    public static function getData($request)
    {
        $string = '?';
        $warranty = self::orderBy('id', 'Desc');
        if (isTrashed($request)) {
            $warranty = $warranty->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('string', $request) && $request['string'] != '') {
            $warranty = $warranty->where('name', 'LIKE', '%' . $request['string'] . '%');
            create_paginate_url($string, 'string=' . $request['string']);
        }
        $warranty = $warranty->paginate(5);
        $warranty->withPath($string);
        return $warranty;
    }
}

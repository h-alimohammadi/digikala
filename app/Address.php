<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;
    protected $table = 'address';
    protected $fillable = ['name', 'mobile', 'province_id', 'city_id', 'address', 'zip_code', 'user_id', 'lat', 'lng'];

    public static function addAddress($request)
    {
        $address = new Address($request->all());
        $address->user_id = $request->user()->id;
        $id = $request->get('id');
        if ($id == 0) {
            if ($address->save()) {
                return Address::with(['province', 'city'])->where('user_id', $request->user()->id)->orderBy('id', 'desc')->get();
            } else {
                return 'error';
            }
        } else {
            $address = Address::where(['id' => $id, 'user_id' => $request->user()->id])->first();
            if ($address) {
                $address->update($request->all());
                return Address::with(['province', 'city'])->where('user_id', $request->user()->id)->orderBy('id', 'desc')->get();
            } else {
                return 'error';
            }
        }
    }

    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'province_id')->select(['id', 'name'])->withDefault(['name' => '']);
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id')->select(['id', 'name'])->withDefault(['name' => '']);
    }
}

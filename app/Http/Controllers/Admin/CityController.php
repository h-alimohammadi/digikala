<?php

namespace App\Http\Controllers\Admin;


use App\City;
use App\Http\Requests\CityRequest;
use App\Province;
use Illuminate\Http\Request;

class CityController extends CustomController
{
    protected $model = 'City';
    protected $title = 'شهر';
    protected $route_params = 'city';

    public function index(Request $request)
    {
        $citys = City::getData($request->all());
        $trashed_city_count = City::onlyTrashed()->count();
        return view('city.index', compact('citys', 'trashed_city_count','request'));
    }

    public function create()
    {
        $provinces = Province::orderBy('id','desc')->pluck('name','id')->toArray();
        return view('city.create', compact('provinces'));
    }

    public function store(CityRequest $request)
    {
        $city = new City($request->all());
        $city->save();
        return redirect('admin/city')->with('message', 'ثبت شهر با موفقیت انجام شد.');
    }

    public function edit(City $city)
    {
        $provinces = Province::orderBy('id','desc')->pluck('name','id')->toArray();
        return view('city.edit', compact('city', 'provinces'));
    }

    public function update(City $city, CityRequest $request)
    {
        $city->update($request->all());
        return redirect('admin/city')->with('message', 'ویرایش شهر با موفقیت انجام شد.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Province;
use Illuminate\Http\Request;

class ProvinceController extends CustomController
{
    protected $model = 'Province';
    protected $title = 'استان';
    protected $route_params = 'province';

    public function index(Request $request)
    {
        $provinces = Province::getData($request->all());
        $trashed_pro_count = Province::onlyTrashed()->count();
        return view('province.index', compact('provinces', 'trashed_pro_count','request'));
    }

    public function create()
    {
        return view('province.create');
    }

    public function store(Request $request)
    {

        $this->validate($request,['name'=>'required'],[],['name'=>'نام استان']);
        $category = new Province($request->all());
        $category->save();
        return redirect('admin/province')->with('message', 'ثبت استان با موفقیت انجام شد.');
    }

    public function edit(Province $province)
    {
        return view('province.edit', compact('province'));
    }

    public function update(Province $province, Request $request)
    {
        $this->validate($request,['name'=>'required'],[],['name'=>'نام استان']);
        $province->update($request->all());
        return redirect('admin/province')->with('message', 'ویرایش استان با موفقیت انجام شد.');
    }
}

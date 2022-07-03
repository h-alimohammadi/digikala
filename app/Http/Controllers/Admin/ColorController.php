<?php

namespace App\Http\Controllers\Admin;

use App\Color;
use Illuminate\Http\Request;

class ColorController extends CustomController
{
    protected $model = 'Color';
    protected $title = 'رنگ';
    protected $route_params = 'color';

    public function index(Request $request)
    {
        session_start();
        if (isset($_GET['page']))
            $_SESSION['page'] = '?page=' . $_GET['page'];
        $colors = Color::getData($request->all());
        $trashed_color_count = Color::onlyTrashed()->count();
        return view('color.index', compact('colors', 'trashed_color_count','request'));
    }

    public function create()
    {
        return view('color.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'code'=>'required',
        ],[],[
            'name'=>'نام رنگ',
            'code'=>'کد رنگ',
        ]);
        $brand = new Color($request->all());
        $brand->save();
        return redirect('admin/color')->with('message', 'ثبت برند با موفقیت انجام شد.');
    }

    public function edit(Color $color)
    {
        return view('color.edit', compact('color'));
    }

    public function update(Color $color, Request $request)
    {
//        session_start();
//        if (isset($_SESSION['page']))
//            $page = $_SESSION['page'];
//        else
//            $page = '';

        $color->update($request->all());
        return redirect('admin/color')->with('message', 'ویرایش برند با موفقیت انجام شد.');
    }
}

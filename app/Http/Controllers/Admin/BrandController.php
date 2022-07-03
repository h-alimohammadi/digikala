<?php

namespace App\Http\Controllers\Admin;


use App\Brand;
use Illuminate\Http\Request;

class BrandController extends CustomController
{
    protected $model = 'Brand';
    protected $title = 'برند';
    protected $route_params = 'brand';

    public function index(Request $request)
    {
        session_start();
        if (isset($_GET['page']))
            $_SESSION['page'] = '?page=' . $_GET['page'];
        $brands = Brand::getData($request->all());
        $trashed_brand_count = Brand::onlyTrashed()->count();
        return view('brand.index', compact('brands', 'trashed_brand_count','request'));
    }

    public function create()
    {
        return view('brand.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'ename'=>'required',
        ],[],[
            'name'=>'نام برند',
            'ename'=>'نام لاتین برند',
        ]);
        $brand = new Brand($request->all());
        $brand->ename = get_url($request->get('ename'));
        $img_url = upload_file($request, 'icon', 'brand');
        $brand->icon = $img_url;
        $brand->save();
        return redirect('admin/brand')->with('message', 'ثبت برند با موفقیت انجام شد.');
    }

    public function edit(Brand $brand)
    {
        return view('brand.edit', compact('brand'));
    }

    public function update(Brand $brand, Request $request)
    {
//        session_start();
//        if (isset($_SESSION['page']))
//            $page = $_SESSION['page'];
//        else
//            $page = '';
        $data = $request->all();
        $brand->ename = get_url($request->get('ename'));
        $img_url = upload_file($request, 'icon', 'brand');
        if ($img_url != null) {
            $data['icon'] = $img_url;
            remove_file($brand->icon, 'brand/');
        }

        $brand->update($data);
        return redirect('admin/brand')->with('message', 'ویرایش برند با موفقیت انجام شد.');
    }


}

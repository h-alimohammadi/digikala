<?php

namespace App\Http\Controllers\Admin;

use App\Warranty;
use Illuminate\Http\Request;

class WarrantyController extends CustomController
{
    protected $model = 'Warranty';
    protected $title = 'گارانتی';
    protected $route_params = 'warranty';

    public function index(Request $request)
    {
        $warranties = Warranty::getData($request->all());
        $trashed_warr_count = Warranty::onlyTrashed()->count();
        return view('warranty.index', compact('warranties', 'trashed_warr_count','request'));
    }

    public function create()
    {
        return view('warranty.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required'
        ]);

        $category = new Warranty($request->all());
        $category->save();
        return redirect('admin/warranty')->with('message', 'ثبت گارانتی با موفقیت انجام شد.');
    }

    public function edit(Warranty $warranty)
    {
        return view('warranty.edit', compact('warranty'));
    }

    public function update(Warranty $warranty, Request $request)
    {
        $warranty->update($request->all());
        return redirect('admin/warranty')->with('message', 'ویرایش گارانتی با موفقیت انجام شد.');
    }
}

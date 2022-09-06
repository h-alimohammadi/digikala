<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\DiscountCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use Illuminate\Http\Request;

class DiscountController extends CustomController
{
    protected $model = 'DiscountCode';
    protected $title = 'تخفیف';
    protected $route_params = 'discount';

    public function index(Request $request)
    {
        $discounts = DiscountCode::getData($request->all());
        $trashed_disc_count = DiscountCode::onlyTrashed()->count();
        return view('discount.index', compact('discounts', 'trashed_disc_count','request'));
    }
    public function create()
    {
        $cat = Category::getParents();
        return view('discount.create', compact('cat'));
    }

    public function store(DiscountRequest $request)
    {
        $incredible_offers = $request->has('incredible_offers') ? 1 : 0;
        $date = getTimestamp($request->get('expire_time'), 'last');
        $discount = new DiscountCode($request->all());
        $discount->incredible_offers = $incredible_offers;
        $discount->expire_time = $date;
        $discount->save();
        return redirect('admin/discount')->with('message','کد تخفیف با موفقیت ثبت شد.');

    }

    public function edit($id)
    {
        $cat = Category::getParents();
        $discount = DiscountCode::where('id',$id)->firstOrFail();
        return view('discount.edit',compact('cat','discount'));
    }
    public function update($id,DiscountRequest $request)
    {
        $data=$request->all();
        $discount = DiscountCode::where('id',$id)->firstOrFail();

        $data['incredible_offers'] = $request->has('incredible_offers') ? 1 : 0;
        $data['expire_time'] = getTimestamp($request->get('expire_time'), 'last');
        $discount->update($data);
        return redirect('admin/discount')->with('message','کد تخفیف با موفقیت ویرایش شد.');

    }
}

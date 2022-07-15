<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemValue;
use App\Product;
use App\ProductWarranty;
use App\Slider;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;

class SiteController extends Controller
{
    public function __construct()
    {
        getCatList();
    }

    public function index()
    {
        $sliders = Slider::orderBy('id', 'desc')->get();
        $productWarranty = ProductWarranty::with('product.category')
            ->with(['itemValue' => function ($query) {
                $query->with('importantItem')->whereHas('importantItem')->limit(2);
            }])
            ->where('offers', 1)->limit(10)->get()->unique('product_id');
        $newProduct = Product::where('status', 1)->orderBy('id', 'DESC')->limit(10)->get();
        $bestSellingProduct = Product::where('status', 1)->orderBy('order_number', 'DESC')->limit(10)->get();
        return view('Shop.index', compact('sliders', 'productWarranty', 'newProduct', 'bestSellingProduct'));
    }

    public function showProduct($product_id, $product_url = null)
    {
        $id = str_replace('dkp-', '', $product_id);
        $where = ['id' => $id];
        if ($product_url != null) {
            $where['product_url'] = $product_url;
        }
        $product = Product::with('brand', 'category', 'getColor.color', 'productWarranties.warranty')->where($where)->firstOrFail();
        $productItems = Item::getProductItems($product);
        $productItemCount = ItemValue::where('product_id', $product->id)->count();
        $relateProducts = Product::where(['cat_id' => $product->cat_id, 'brand_id' => $product->brand_id])->where('id', '!=', $product->id)->get();
//        return $productItems;
        return view('Shop.show_product', compact('product', 'productItems', 'productItemCount', 'relateProducts'));

    }

    public function changeColor(Request $request)
    {
        $color_id = $request->get('color_id');
        $product_id = $request->get('product_id');
        $product = Product::with('getColor.color', 'productWarranties.warranty')->where('id', $product_id)->first();
        $checkHasColor = ProductWarranty::where(['color_id' => $color_id, 'product_id' => $product_id])->where('product_number', '>', 0)->first();
        if ($product && $checkHasColor)
            return view('Include.warranty', compact('product', 'color_id'));
        else
            return false;
    }

    public function confirm()
    {
        if (Session::has('mobile_number')) {
            return view('auth.confirm');
        } else {
            return redirect('/');
        }
    }

    public function resend(Request $request)
    {
        $active_code = rand(99999, 1000000);
        $mobile = $request->get('mobile');
        if ($request->ajax()) {
            $user = User::where(['mobile' => $mobile, 'account_status' => 'InActive'])->first();
            if ($user) {
                $user->active_code = $active_code;
                $user->update();
                return 'Ok';
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }

    }

    public function activeAccount(Request $request)
    {
        $mobile = $request->get('mobile');
        $active_code = $request->get('active_code');
        $user = User::where(['mobile' => $mobile, 'active_code' => $active_code, 'account_status' => 'InActive'])->first();
        if ($user) {
            $user->account_status = 'active';
            $user->active_code = '';
            $user->update();
            Auth::guard()->login($user);
            return redirect('/');
        } else {
            return redirect()->back()->with('mobile_number', $mobile)
                ->with('validate_error', 'کد وارد شده اشتباه است.')->withInput();
        }
    }
}

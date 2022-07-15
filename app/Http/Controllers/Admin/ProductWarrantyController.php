<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductWarrantyRequest;
use App\Product;
use App\ProductColor;
use App\ProductWarranty;
use App\Warranty;
use Illuminate\Http\Request;

class ProductWarrantyController extends CustomController
{
    protected $model = 'ProductWarranty';
    protected $title = 'تنوع قیمت';
    protected $route_params = 'product_warranties';
    protected $product;
    protected $queryString;

    public function __construct(Request $request)
    {
        $this->product = Product::findOrFail($request->get('product_id'));
        $this->queryString = 'product_id=' . $this->product->id;
    }

    public function index(Request $request)
    {
        $productWarranty = ProductWarranty::getData($request);
        $trashed_pw_count = ProductWarranty::onlyTrashed()->count();
        return view('product_warranties.index', [
            'productWarranty' => $productWarranty,
            'trashed_pw_count' => $trashed_pw_count,
            'product' => $this->product,
        ]);
    }

    public function create()
    {
        $warranty = Warranty::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        $colors = ProductColor::with('color')->where('product_id', $this->product->id)->get();
        return view('product_warranties.create', [
            'warranty' => $warranty,
            'colors' => $colors,
            'product' => $this->product,
        ]);
    }

    public function store(ProductWarrantyRequest $request)
    {
        $check = ProductWarranty::where([
            'seller_id' => 0,
            'product_id' => $this->product->id,
            'warranty_id' => $request->get('warranty_id'),
            'color_id' => $request->get('color_id'),
        ])->first();
        if ($check) {
//            $check->update([
//                'price1' => $request->get('price1'),
//                'price2' => $request->get('price2'),
//            ]);
            return redirect()->back()->withInput()->with('warning', ' تنوع قیمت انتخابی قبلا ثبت شده است.');

        } else {
            $productWarranty = new ProductWarranty($request->all());
            $productWarranty->saveOrFail();
            add_min_product_price($productWarranty);
            update_product_price($this->product);
            return redirect('admin/product_warranties?product_id=' . $this->product->id)->with('message', 'ثبت تنوع قیمت با موفقیت انجام شد.');
        }
    }

    public function edit($id)
    {
        $productWarranty = ProductWarranty::findOrFail($id);
        $warranty = Warranty::orderBy('id', 'desc')->pluck('name', 'id')->toArray();
        $colors = ProductColor::with('color')->where('product_id', $this->product->id)->get();
        return view('product_warranties.edit', [
            'warranty' => $warranty,
            'productWarranty' => $productWarranty,
            'colors' => $colors,
            'product' => $this->product,
        ]);
    }

    public function update(ProductWarranty $productWarranty, ProductWarrantyRequest $request)
    {
//        return $request;

        $productWarranty->update($request->all());
        add_min_product_price($productWarranty);
        update_product_price($this->product);
        return redirect('admin/product_warranties?product_id=' . $this->product->id)->with('message', 'ویرایش تنوع قیمت با موفقیت انجام شد.');
    }

}

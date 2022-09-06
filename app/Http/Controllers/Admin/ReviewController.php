<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends CustomController
{
    protected $model = 'Review';
    protected $title = 'نقد و بررسی';
    protected $route_params = 'product/review';
    protected $route_params2 = 'review';
    protected $queryString;
    protected $product;


    public function __construct(Request $request)
    {
        $this->product = Product::where('id', $request->get('product_id'), 0)->firstOrFail();
        $this->queryString = 'product_id=' . $this->product->id;
    }

    public function index(Request $request)
    {
        $product = $this->product;
        $reviews = Review::getData($request->all());
        $trashed_reviews_count = Review::onlyTrashed()->count();
        return view('review.index', compact('reviews', 'trashed_reviews_count', 'product', 'request'));
    }

    public function create()
    {
        return view('review.create', ['product' => $this->product]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'tozihat' => 'required',
        ], [], [
            'title' => 'عنوان',
            'tozihat' => 'توضیحات',
        ]);
        $review = new Review($request->all());
        $review->product_id = $this->product->id;
        $review->saveOrFail();
        return redirect('admin/product/review/?product_id=' . $this->product->id)->with('message', 'ثبت نقد و بررسی با موفقیت انجام شد.');
    }

    public function edit($id)
    {
        $review = Review::where('id', $id)->firstOrFail();
        $product = $this->product;

        return view('review.edit', compact('review', 'product'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'tozihat' => 'required',
        ], [], [
            'title' => 'عنوان',
            'tozihat' => 'توضیحات',
        ]);
        $review = Review::where('id', $id)->firstOrFail();

        $review->product_id = $this->product->id;
        $review->update($request->all());
        return redirect('admin/product/review/?product_id=' . $this->product->id)->with('message', 'ویرایش نقد و بررسی با موفقیت انجام شد.');
    }

    public function primary()
    {
        $PrimaryContent = Review::whereNull('title')->where('product_id', $this->product->id)->first();
        $tozihat = $PrimaryContent ? $PrimaryContent->tozihat : '';
        return view('review.primary',['product'=>$this->product,'tozihat'=>$tozihat]);
    }

    public function addPrimaryContent(Request $request)
    {

        DB::table('review_product')->whereNull('title')->where('product_id', $this->product->id)->delete();
        if (!empty($request->get('tozihat'))){
            $review = new Review($request->all());
            $review->product_id = $this->product->id;
            $review->saveOrFail();
        }
        return redirect('admin/product/review/?product_id=' . $this->product->id)->with('message', 'ثبت توضیحات با موفقیت انجام شد.');

    }


}

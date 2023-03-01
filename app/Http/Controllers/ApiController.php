<?php

namespace App\Http\Controllers;

use App\City;
use App\Comment;
use App\ProductWarranty;
use App\Province;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getComment(Request $request)
    {
        return Comment::getCommentProductList($request->get('product_id', 0),$request->get('orderBy', 1));
    }

    public function getProvince()
    {
        sleep(5);
        return Province::orderBy('name', 'ASC')->get();
    }

    public function getCity($id)
    {
        $row = Province::find($id);
        if ($row) {
            $province = City::where('province_id', $id)->orderBy('name', 'ASC')->get();
            return $province;
        } else {
            return 'error';
        }
    }

    public function commentLike(Request $request)
    {
        $comment = Comment::addUserScore($request->get('comment_id'),'like');
        return $comment;
    }
    public function commentDislike(Request $request)
    {
        $comment = Comment::addUserScore($request->get('comment_id'),'dislike');
        return $comment;
    }
    public function getProductChartData($product_id)
    {
        return get_product_price_changed($product_id);
    }
    public function getProductWarranty(Request $request)
    {
        $product_id=$request->post('product_id',0);
        $color_id=$request->post('color_id',0);
        return ProductWarranty::where(['product_id'=>$product_id,'color_id'=>$color_id])->orderBy('price2','ASC')
            ->with('seller:id,brand_name')
            ->with('warranty:id,name')->get();
    }
}

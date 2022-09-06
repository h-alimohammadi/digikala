<?php

namespace App\Http\Controllers;

use App\City;
use App\Comment;
use App\Province;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getComment(Request $request)
    {
        $comments = Comment::getCommentProductList($request->get('product_id', 0),$request->get('orderBy', 1));
        return $comments;
    }

    public function getProvince()
    {
        $province = Province::orderBy('name', 'ASC')->get();
        return $province;
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
}

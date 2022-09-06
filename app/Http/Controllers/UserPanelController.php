<?php

namespace App\Http\Controllers;

use App\Comment;
use App\GiftCart;
use App\Order;
use App\OrderData;
use Illuminate\Http\Request;

class UserPanelController extends Controller
{
    public function __construct()
    {
        getCatList();
    }
    public function giftCart(Request $request)
    {
        $user_id=$request->user()->id;
        $giftCart=GiftCart::where('user_id',$user_id)->orderBy('id','DESC')->paginate(15);
        return view('userPanel.gift_cart',compact('giftCart'));
    }

    public function orders(Request $request)
    {
        $user_id=$request->user()->id;
        $orders = Order::where('user_id',$user_id)->orderBy('id','DESC')->paginate(10);
        return view('userPanel.orders',compact('orders'));
    }

    public function showOrders($order_id,Request $request)
    {
        $user_id=$request->user()->id;
        $order = Order::with(['getProductRow', 'getOrderInfo', 'getAddress','getGiftCart'])->where(['id' => $order_id,'user_id'=>$user_id])->firstOrFail();
        $order_data = new OrderData($order->getOrderInfo, $order->getProductRow, $order->user_id);
        $order_data = $order_data->getData();
        return view('userPanel.show_order',compact('order','order_data'));

    }
    public function commentLike(Request $request)
    {
        $comment = Comment::where('id',$request->get('comment_id'))->firstOrFail();
        $like = $comment->like + 1;
        if ($comment->update(['like' => $like])) {
            return 'Ok';
        } else {
            return 'error';
        }
    }
}

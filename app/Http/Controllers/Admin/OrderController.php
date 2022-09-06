<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderData;
use App\OrderInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends CustomController
{
    protected $model = 'Order';
    protected $title = 'سفارشات';
    protected $route_params = 'orders';

    public function index(Request $request)
    {
        $orders = Order::getData($request->all());
        $trashed_order_count = Order::onlyTrashed()->count();
        return view('orders.index', compact('orders', 'trashed_order_count', 'request'));

    }

    public function show($order_id)
    {
        $order = Order::with(['getProductRow', 'getOrderInfo', 'getAddress','getGiftCart'])->where(['id' => $order_id])->firstOrFail();
        if ($order->order_read == 'no') {
            $order->order_read = 'Ok';
            $order->update();
        }
        $order_data = new OrderData($order->getOrderInfo, $order->getProductRow,$order->user_id,'no');
        $order_data = $order_data->getData();
        return view('orders.show', compact('order', 'order_data'));
    }

    public function changeStatus(Request $request)
    {
        $order_id = $request->get('order_id');
        $status = $request->get('status');
        $order_info = OrderInfo::where('id', $order_id)->first();
        if ($order_info) {

            DB::beginTransaction();
            $order_info->send_status = $status;
            try {
                $order_info->update();
                set_order_product_status($order_info, $status);
                DB::commit();
                return 'Ok';
            } catch (\Exception $exception) {
                DB::rollBack();
                return 'error';
            }

        }
    }

    public function submission(Request $request)
    {
        $submission=OrderInfo::getData($request->all(),0,'ASC');
        $label='مدیریت مرسوله ها';
        $label_url='submission';
        return view('orders.submission',compact('submission','label','label_url','request'));
    }

    public function submissionInfo($id)
    {
        $submissionInfo=OrderInfo::with('getOrder.getAddress')->has('getOrder')->where('id',$id)->firstOrFail();
        $order_data = new OrderData($submissionInfo->getOrder->getOrderInfo, $submissionInfo->getOrder->getProductRow, $submissionInfo->getOrder->user_id);
        $order_data = $order_data->getData($id);
        return view('orders.submission_info',compact('submissionInfo','order_data'));
    }

    public function submissionApproved(Request $request)
    {
        $submission=OrderInfo::getData($request->all(),1);
        $label=' مرسوله های تایید شده';
        $label_url='submission/approved';
        return view('orders.submission',compact('submission','label','label_url','request'));
    }

    public function itemsToday(Request $request)
    {
        $submission=OrderInfo::getData($request->all(),2);
        $label='مرسوله های ارسالی امروز';
        $label_url='submission/items/today';
        return view('orders.submission',compact('submission','label','label_url','request'));
    }
    public function ready(Request $request)
    {
        $submission=OrderInfo::getData($request->all(),3);
        $label='مرسوله های آماده ارسال';
        $label_url='submission/ready';
        return view('orders.submission',compact('submission','label','label_url','request'));
    }
    public function postingSend(Request $request)
    {
        $submission=OrderInfo::getData($request->all(),4);
        $label='مرسوله های ارسال شده به پست';
        $label_url='posting/send';
        return view('orders.submission',compact('submission','label','label_url','request'));
    }
    public function postingReceive(Request $request)
    {
        $submission=OrderInfo::getData($request->all(),5);
        $label='مرسوله های آماده دریافت از پست';
        $label_url='posting/receive';
        return view('orders.submission',compact('submission','label','label_url','request'));
    }
    public function deliveredShipping(Request $request)
    {
        $submission=OrderInfo::getData($request->all(),6);
        $label='مرسوله های تحویل داده شده';
        $label_url='delivered/shipping';
        return view('orders.submission',compact('submission','label','label_url','request'));
    }
}

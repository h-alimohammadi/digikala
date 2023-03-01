<?php

namespace App\Http\Controllers;


use App\Address;
use App\Cart;
use App\City;
use App\DiscountCode;
use App\GiftCart;
use App\Lib\Mobile_Detect;
use App\Order;
use App\OrderData;
use App\OrderingTime;
use App\Product;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ShoppingController extends Controller
{
    protected $view='';
    public function __construct()
    {
        getCatList();
        $this->middleware('auth');
        $detect=new Mobile_Detect();
        if ($detect->isMobile() || $detect->isTablet()){
            $this->view='mobile/';
        }
    }

    public function shipping(Request $request)
    {
        if (Cart::getProductCount() > 0) {
            $address = Address::with(['province', 'city'])->where('user_id', $request->user()->id)->orderBy('id', 'desc')->get();
            return view($this->view.'shipping.set_data', compact('address'));
        } else {
            return redirect('/');
        }
    }

    public function getSendData($city_id)
    {
        $a = Product::all();
        $orderingTime = new OrderingTime($city_id);
        return $orderingTime->getGlobalSendData();
    }

    public function getProvince()
    {
        $province = Province::orderBy('id', 'desc')->get();
        return $province;
    }

    public function getCity($id)
    {
        $row = Province::find($id);
        if ($row) {
            $province = City::where('province_id', $id)->orderBy('id', 'desc')->get();
            return $province;
        } else {
            return 'error';
        }

    }

    public function payment(Request $request)
    {
        if (Cart::getProductCount() > 0) {
            $address_id = $request->get('address_id');
            $address = Address::where(['id' => $address_id, 'user_id' => $request->user()->id])->first();
            $send_type = $request->get('sent_type', 1);
            if ($address) {
                Session::put('order_address_id', $address_id);
                Session::put('order_send_type', $send_type);
                $orderingTime = new OrderingTime($address->city_id);
                $send_order_data = $orderingTime->getGlobalSendData();
                $cart_final_price = $send_type == 1 ? $send_order_data['integer_normal_cart_price'] : $send_order_data['integer_fasted_cart_amount'];
                Session::put('cart_final_price', $cart_final_price);


                return view('shipping.payment', compact('send_order_data', 'send_type'));
            } else {
                return redirect('/shipping');
            }
        } else {
            return redirect('/');
        }
        return $request;
    }

    public function orderPayment(Request $request)
    {
        $address_id = Session::get('order_address_id');
        if ($address_id) {
            $address = Address::where(['id' => $address_id, 'user_id' => $request->user()->id])->first();
            if ($address) {
                $orderingTime = new OrderingTime($address->city_id);
                $send_order_data = $orderingTime->getGlobalSendData();
                $order = new Order();
                return $order->addOrder($send_order_data);
            } else {
                return redirect('/shipping');
            }
        } else {
            return redirect('/shipping');

        }
    }

    public function verify()
    {
        $order_id = 14;
        DB::beginTransaction();
        try {
            $order = Order::with(['getProductRow', 'getOrderInfo', 'getAddress','getGiftCart'])->where(['id' => $order_id])->firstOrFail();
            $order->pay_status = 'Ok';
            $order->update();
            $order_data = new OrderData($order->getOrderInfo, $order->getProductRow, $order->user_id, 'yes');
            $order_data = $order_data->getData();
            if (Session::has('gift_value') && Session::get('gift_value') > 0) {
                $gift_value = Session::get('gift_value');
                $gift_id = Session::get('gift_cart');
                $giftCart = GiftCart::where('id', $gift_id)->first();
                if ($giftCart) {
                    $giftCart->credit_used += $gift_value;
                    $giftCart->update();
                }
                Session::forget('gift_value');
                Session::forget('gift_cart');
            }
            if (Session::has('discount_value') && Session::get('discount_value') > 0) {
                $discount_value = Session::get('discount_value');
                $discount_code = Session::get('discount_code');
                $giftCart = DiscountCode::where('code', $discount_code)->get();
                if ($giftCart) {
                    $giftCart->credit_used = $gift_value;
                    $giftCart->update();
                }
                Session::forget('gift_value');
                Session::forget('gift_cart');
            }
            DB::table('order_infos')->where('order_id', $order_id)->update(['send_status' => 1]);
            DB::table('order_products')->where('order_id', $order_id)->update(['send_status' => 1]);
            DB::commit();
            return view('shipping.verify', compact('order', 'order_data'));

        } catch (\Exception $exception) {
            DB::rollBack();
            $error_payment = 'خطا در ثبت اطلاعات، لطفا برای بررسی خطا پیش آمده با پشتیبانی فروشگاه در ارتباط باشید.';
            return view('shipping.verify', compact('order', 'order_data', 'error_payment'));
        }


    }

    public function checkGiftCart(Request $request)
    {
        $code = $request->get('code');
        $giftCart = GiftCart::where('code', $code)->first();
        if ($giftCart) {
            $cart_final_price = Session::get('cart_final_price', 0);
            if (Session::get('gift_value', 0) > 0) {
                $cart_final_price = $cart_final_price + Session::get('gift_value', 0);
            }
            if (($giftCart->credit_cart - $giftCart->credit_used) > 0) {
                $use = $giftCart->credit_cart - $giftCart->credit_used;

                if ($cart_final_price < $use) {
                    $use = $cart_final_price;
                }

                Session::put('gift_value', $use);
                Session::put('gift_cart', $giftCart->id);
                $cart_final_price = $cart_final_price - $use;
                return [
                    'status' => 'Ok',
                    'gift_value' => replace_number(number_format($use)) . ' تومان',
                    'cart_final_price' => replace_number(number_format($cart_final_price)) . ' تومان',
                ];
            } else {
                return 'اعتبار کارت هدیه برای استفاده به اتمام رسیده';
            }
        } else {
            return 'کارت هدیه وارد شده اشتباه میباشد.';
        }
    }

    public function checkDiscountCode(Request $request)
    {
        $code = $request->get('code');
        $time = time();
        $discounts = DiscountCode::where('code', $code)->where('expire_time', '>=', $time)->get();
        if (isset($discounts[0])) {
            return DiscountCode::check($discounts);
        } else {
            return 'کد تخفیف وارد شده اشتباه میباشد.';
        }
    }


}

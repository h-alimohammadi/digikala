<?php

namespace App;

use App\Lib\Jdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;


class Order extends Model
{
    protected $colors_id = '';
    use SoftDeletes;
    protected $table = 'orders';
    protected $dateFormat = 'U';
    protected $fillable = ['date', 'user_id', 'address_id', 'pay_status', 'total_price', 'price', 'order_id',
        'pay_code1', 'pay_code2', 'order_read', 'send_type', 'discount_value', 'discount_code', 'gift_value', 'gift_id'];


    public function addOrder($order_data = '')
    {

        $user_id = Auth::user()->id;
        $order_send_type = Session::get('order_send_type');
        $order_address_id = Session::get('order_address_id');
        $time = time();
        $order_code = substr($time, 1, 5) . $user_id . substr($time, 5, 10);
        $jdf = new Jdf();
        $this->user_id = $user_id;
        $this->address_id = $order_address_id;
        $this->order_read = 'no';
        $this->pay_status = 'awaiting_payment';
        $this->order_id = $order_code;
        $this->date = $order_code;
        $this->send_type = $order_send_type;
        $final_price=Session::get('final_price',0);
        if ($this->send_type == 1) {
            $final_price= $final_price +$order_data['integer_normal_send_order_amount'];
            $this->price = $order_data['integer_normal_cart_price'];
            $this->total_price = $final_price;
        } else {
            $final_price= $final_price +$order_data['integer_total_fast_send_amount'];
            $this->price = $order_data['integer_fasted_cart_amount'];
            $this->total_price = $final_price;
        }
        if (Session::has('gift_value') && Session::get('gift_value')>0) {
            $this->gift_value=Session::get('gift_value');
            $this->gift_id=Session::get('gift_cart');
        }
        if (Session::has('discount_value') && Session::get('discount_value')>0) {
            $this->discount_value=Session::get('discount_value');
            $this->discount_code=Session::get('discount_code');
        }
            DB::beginTransaction();
        try {
            $this->save();
            $this->add_order_row($order_data);
            DB::commit();
            return [
                'status' => 'Ok',
                'order_id' => $this->id,
                'price' => $this->price,
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            return [
                'status' => 'error',
            ];
        }
    }

    private function add_order_row($order_data)
    {
        $time = time();
        if (array_key_exists('cart_product_data', $order_data)) {
            foreach ($order_data['cart_product_data'] as $key => $value) {
                DB::table('order_products')->insert([
                    'order_id' => $this->id,
                    'product_id' => $value['product_id'],
                    'color_id' => $value['color_id'],
                    'warranty_id' => $value['warranty_id'],
                    'product_price1' => $value['price1'],
                    'product_price2' => $value['price2'],
                    'product_count' => $value['product_count'],
                    'seller_id' => $value['seller_id'],
                    'time' => $time,
                    'preparation_time' => $value['send_day'],
                ]);
            }
            $this->add_order_info($order_data);
        }
    }

    private function add_order_info($order_data)
    {

        $this->colors_id = '';
        $jdf = new Jdf();
        $h = $jdf->tr_num($jdf->jdate('H'));
        $h = (24 - $h);
        if ($this->send_type == 1) {
            $order_send_time = $order_data['normal_send_day'];
            settype($order_send_time, 'integer');
            $time = $order_send_time * 24 * 60 * 60;
            $order_send_time = time() + $time + ($h * 60 * 60);

            $order_info = new OrderInfo();
            $order_info->order_id = $this->id;
            $order_info->delivery_order_interval = $order_data['min_ordering_day'] . '  تا  ' . $order_data['max_ordering_day'];
            $order_info->send_order_amount = $order_data['integer_normal_send_order_amount'];
            $order_info->send_status = 0;
            $order_info->order_send_time = $order_send_time;
            $order_info->product_id = $this->get_product_id($order_data);
            $order_info->warranty_id = $this->get_warranty_id($order_data);
            $order_info->colors_id = $this->colors_id;
            $order_info->save();

        } else {
            foreach ($order_data['delivery_order_interval'] as $key => $value) {
                $order_send_time = $value['send_order_day_number'];
                settype($order_send_time, 'integer');
                $time = $order_send_time * 24 * 60 * 60;
                $order_send_time = time() + $time + ($h * 60 * 60);

                $order_info = new OrderInfo();
                $order_info->order_id = $this->id;
                $order_info->delivery_order_interval = $value['dayLabel1'] . ' تا ' . $value['dayLabel2'];
                $order_info->send_order_amount = $value['integer_send_fast_price'];
                $order_info->product_id = $this->get_fasted_send_product_id($order_data, $key);
                $order_info->warranty_id = $this->get_fasted_send_warranty_id($order_data, $key);
                $order_info->colors_id = $this->get_fasted_send_color_id($order_data, $key);
                $order_info->send_status = 0;
                $order_info->order_send_time = $order_send_time;
                $order_info->save();

            }
        }
    }

    private function get_fasted_send_product_id($order_data, $key)
    {
        $collecton = collect($order_data['array_product_id'][$key]);
        $product_id = $collecton->implode('-');
        return $product_id;
    }

    private function get_fasted_send_warranty_id($order_data, $key)
    {

        $collecton = collect($order_data['array_warranty_id'][$key]);
        $product_id = $collecton->implode('-');
        return $product_id;
    }

    private function get_fasted_send_color_id($order_data, $key)
    {
        $collecton = collect($order_data['array_colors_id'][$key]);
        $colors_id = $collecton->implode('-');
        return $colors_id;
    }

    private function get_product_id($order_data)
    {
        $products_id = '';
        $j = 0;
        foreach ($order_data['cart_product_data'] as $key => $value) {
            $products_id = $products_id . $value['product_id'];
            if ($j != (sizeof($order_data['cart_product_data']) - 1)) {
                $products_id .= "-";
            }
            $j++;
        }
        return $products_id;
    }

    private function get_warranty_id($order_data)
    {
        $j = 0;
        $warranty_id = '';
        foreach ($order_data['cart_product_data'] as $key => $value) {
            $warranty_id .= $value['warranty_id'];
            $this->colors_id .= $value['color_id'];
            if ($j != (sizeof($order_data['cart_product_data']) - 1)) {
                $warranty_id = $warranty_id . "-";
                $this->colors_id = $this->colors_id . "-";
            }
            $j++;
        }
        return $warranty_id;
    }

    public function getProductRow()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id')
            ->with(['getProduct', 'getColor', 'getWarranty']);
    }
    public function getOrderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function getOrderInfo()
    {
        return $this->hasMany(OrderInfo::class, 'order_id', 'id');
    }

    public function getAddress()
    {
        return $this->hasOne(Address::class, 'id', 'address_id')
            ->with(['city', 'province'])->withDefault(['name' => ''])->withTrashed();
    }
    public function getGiftCart()
    {
        return $this->hasOne(GiftCart::class, 'id', 'gift_id');
    }


    public static function orderStatus()
    {
        $array = [];
        $array[-2] = 'خطا در اتصال به درگاه';
        $array[-1] = 'لغو شده';
        $array[0] = 'در انتظار پرداخت';
        $array[1] = 'تایید سفارش';
        $array[2] = 'آماده سازی سفارش';
        $array[3] = 'خروج از مرکز پردازش';
        $array[4] = 'تحویل به پست';
        $array[5] = 'دریافت از مرکز مبادلات';
        $array[6] = 'تحویل مرسوله به مشتری';
        return $array;
    }

    public static function getOrderStatus($status, $orderStatus)
    {
        return $orderStatus[$status];
    }

    public static function getData($request)
    {
        $string = '?';
        $order = self::orderBy('id', 'Desc');
        if (isTrashed($request)) {
            $order = $order->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('user_id', $request) && $request['user_id'] != '') {
            $order = $order->where('user_id',$request['user_id']);
            create_paginate_url($string, 'user_id=' . $request['user_id']);
        }
        if (array_key_exists('order_id', $request) && $request['order_id'] != '') {
            $order_id =  replace_number($request['order_id']);
            $order = $order->where('order_id',$order_id);
            create_paginate_url($string, 'order_id=' . $order_id);
        }
        if (array_key_exists('first_date', $request) && $request['first_date'] != '') {
            $first_date=getTimestamp($request['first_date'],'first');
            $order = $order->where('created_at','>=',$first_date);
            create_paginate_url($string, 'first_date=' . $first_date);
        }
        if (array_key_exists('end_date', $request) && $request['end_date'] != '') {
            $first_date=getTimestamp($request['end_date'],'first');
            $order = $order->where('created_at','<=',$first_date);
            create_paginate_url($string, 'end_date=' . $first_date);
        }
        $order = $order->paginate(15);
        $order->withPath($string);
        return $order;
    }
    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

}

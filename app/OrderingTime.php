<?php

namespace App;

use App\Lib\Jdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class OrderingTime extends Model
{
    protected $city_id;
    protected $send_time = 0;
    protected $send_price = 0;
    protected $min_order_price = 0;
    protected $cart_price = 0;
    protected $cart_product_data = [];
    protected $array_product_id = [];
    protected $array_color_id = [];
    protected $array_warranty_id = [];
    protected $order_price_by_fast_send = [];
    protected $minDay = [];
    protected $maxDay = [];
    protected $minTimeStapm = [];
    protected $maxTimeStapm = [];
    protected $dayLabel1 = [];
    protected $dayLabel2 = [];
    protected $send_status = [];
    protected $total_send_order_price = 0;
    protected $fasted_cart_amount = 0;
    protected $normal_send_day = 0;

    public function __construct($city_id)
    {
        $this->city_id = $city_id;
    }

    public function getGlobalSendData()
    {
        $city = City::find($this->city_id);
        if ($city && !empty($city->send_time) && !empty($city->send_price) && !empty($city->min_order_price)) {
            $send_time = $city->send_time;
            $send_price = $city->send_price;
            $min_order_price = $city->min_order_price;
            settype($send_time, 'integer');
            settype($send_price, 'integer');
            settype($min_order_price, 'integer');
            $this->send_time = $send_time;
            $this->send_price = $send_price;
            $this->min_order_price = $min_order_price;
            return $this->getCartData();
        } else {
            $setting = new Setting();
            $values = $setting->getData(['send_time', 'send_price', 'min_order_price']);
            $send_time = $values['send_time'];
            $send_price = $values['send_price'];
            $min_order_price = $values['min_order_price'];
            settype($send_time, 'integer');
            settype($send_price, 'integer');
            settype($min_order_price, 'integer');
            $this->send_time = $send_time;
            $this->send_price = $send_price;
            $this->min_order_price = $min_order_price;
            return $this->getCartData();
        }

    }

    public function getCartData()
    {
        $getCartData = Cart::getCartData('noCart');
        foreach ($getCartData['product'] as $product) {
            $k = $product['product_id'] . '_' . $product['product_warranty_id'];
            $this->cart_product_data[$k] = $product;
            $this->cart_price += $product['price2'];
            $this->self_fast_order_sending_time($product);
        }
        $array = [];
        $array['delivery_order_interval'] = $this->get_delivery_order_interval();
        if ($this->cart_price < $this->min_order_price) {
            $array['normal_send_order_amount'] = replace_number(number_format($this->send_price)) . ' ??????????';
            $array['integer_normal_send_order_amount'] = $this->send_price;
            $normal_cart_price = $this->cart_price += $this->send_price;
            if (Session::has('gift_value') && Session::get('gift_value')>0){
                $normal_cart_price=$normal_cart_price - Session::get('gift_value');
            }
            if (Session::has('discount_value') && Session::get('discount_value')>0){
                $normal_cart_price=$normal_cart_price - Session::get('discount_value');
            }

            $array['normal_cart_price'] = replace_number(number_format($normal_cart_price)) . ' ??????????';
            $array['integer_normal_cart_price'] = $normal_cart_price;
        } else {
            $array['normal_send_order_amount'] = '????????????';
            $array['integer_normal_send_order_amount'] = 0;
            if (Session::has('gift_value') && Session::get('gift_value')>0){
                $this->cart_price=$this->cart_price -Session::get('gift_value');
            }
            if (Session::has('discount_value') && Session::get('discount_value')>0){
                $this->cart_price=$this->cart_price -Session::get('discount_value');
            }
            $array['normal_cart_price'] = replace_number(number_format($this->cart_price)) . ' ??????????';
            $array['integer_normal_cart_price'] = $this->cart_price;
        }
        $fasted_cart_amount = $this->cart_price + $this->total_send_order_price;
        if (Session::has('gift_value') && Session::get('gift_value')>0){
            $fasted_cart_amount=$fasted_cart_amount -Session::get('gift_value');
        }
        if (Session::has('discount_value') && Session::get('discount_value')>0){
            $fasted_cart_amount=$fasted_cart_amount -Session::get('discount_value');
        }
        $array['fasted_cart_amount'] = replace_number(number_format($fasted_cart_amount)) . ' ??????????';
        $array['integer_fasted_cart_amount'] = $this->cart_price + $this->total_send_order_price;

        $array['total_fast_send_amount'] = $this->total_send_order_price == 0 ? '????????????' : replace_number(number_format($this->total_send_order_price)) . ' ??????????';
        $array['integer_total_fast_send_amount'] = $this->total_send_order_price;
        $array['normal_send_day'] = $this->normal_send_day;
        $array['min_ordering_day'] = $this->get_min_ordering_day();
        $array['max_ordering_day'] = $this->get_max_ordering_day();
        $array['cart_product_data'] = $this->cart_product_data;
        $array['array_product_id'] = $this->array_product_id;
        $array['array_colors_id'] = $this->array_color_id;
        $array['array_warranty_id'] = $this->array_warranty_id;
        return $array;
    }

    private function self_fast_order_sending_time($product)
    {
        $day = $product['send_day'];
        $collection = collect($this->send_status);
        $key = $collection->search($day);

        if ($key == false && is_bool($key)) {
            $this->array_product_id[sizeof($this->send_status)][$product['product_warranty_id']] = $product['product_id'];
            $this->array_color_id[sizeof($this->send_status)][$product['product_warranty_id']] = $product['color_id'];
            $this->array_warranty_id[sizeof($this->send_status)][$product['warranty_id']] = $product['warranty_id'];
            $this->order_price_by_fast_send[sizeof($this->send_status)] = $product['price2'];
            $this->send_status[sizeof($this->send_status)] = $day;
        } else {
            $this->array_product_id[$key][$product['product_warranty_id']] = $product['product_id'];
            $this->array_color_id[$key][$product['product_warranty_id']] = $product['color_id'];
            $this->array_warranty_id[$key][$product['warranty_id']] = $product['warranty_id'];
            $this->order_price_by_fast_send[$key] = $this->order_price_by_fast_send[$key] + $product['price2'];
        }
    }

    private function get_delivery_order_interval()
    {
        $jdf = new Jdf();
        $day_array = [];
        foreach ($this->send_status as $key => $value) {
            settype($value, 'integer');
            $day1 = $value + $this->send_time;
            $day2 = $day1 + 3;
            $this->minDay[$key] = $day1;
            $this->maxDay[$key] = $day1;

            $this->minTimeStapm[$key] = strtotime('+ ' . $day1 . 'day');
            $this->maxTimeStapm[$key] = strtotime('+ ' . $day2 . 'day');
            $this->dayLabel1[$key] = $jdf->jdate('j F', $this->minTimeStapm[$key]);
            $this->dayLabel2[$key] = $jdf->jdate('j F', $this->maxTimeStapm[$key]);
            $day_array[$key] = ['dayLabel1' => $this->dayLabel1[$key], 'dayLabel2' => $this->dayLabel2[$key]];
            if ($this->order_price_by_fast_send[$key] < $this->min_order_price) {
                $this->total_send_order_price += $this->send_price;
                $day_array[$key]['send_fast_price'] = replace_number(number_format($this->send_price)) . ' ??????????';
                $day_array[$key]['integer_send_fast_price'] = $this->send_price;
            } else {
                $day_array[$key]['send_fast_price'] = '????????????';
                $day_array[$key]['integer_send_fast_price'] = 0;
            }
            $day_array[$key]['send_order_day_number'] = $value;
            if ($value > $this->normal_send_day) {
                $this->normal_send_day = $value;
            }
        }
        return $day_array;
    }

    private function get_min_ordering_day()
    {
        $collection = collect($this->minDay);
        $max_value = $collection->max();
        $key = $collection->search($max_value);
        return $this->dayLabel1[$key];
    }

    private function get_max_ordering_day()
    {
        $collection = collect($this->maxDay);
        $max_value = $collection->max();
        $key = $collection->search($max_value);
        return $this->dayLabel2[$key];
    }
}
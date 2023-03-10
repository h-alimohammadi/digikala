<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class DiscountCode extends Model
{
    use SoftDeletes;
    protected $table = 'discount_codes';
    protected $fillable = ['code', 'expire_time', 'cat_id', 'amount', 'number_usable', 'incredible_offers', 'amount_discount', 'amount_percent'];

    public static function getData($request)
    {
        $string = '?';
        $discount = self::orderBy('id', 'Desc');
        if (isTrashed($request)) {
            $discount = $discount->onlyTrashed();
            $string = create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('code', $request) && !empty($request['code'])) {
            $discount = $discount->where('code', 'LIKE', '%' . $request['code'] . '%');
            $string = create_paginate_url($string, 'code=' . $request['code']);
        }
        $discount = $discount->paginate(10);
        $discount->withPath($string);
        return $discount;
    }

    public static function check($discounts)
    {
        $cartData = Cart::getCartData();
        $discount_value_array = [];
        $product_price2 = self::get_cart_final_price();
        foreach ($discounts as $discount) {
            $cat_id = $discount->cat_id;
            $price = 0;
            if ($discount->cat_id > 0) {


                foreach ($cartData['product'] as $product) {
                    if ($cat_id == $product['cat_id']) {
                        $price += $product['price1']*$product['product_count'];
                    }
                }
                $product_price2 = $product_price2 - $price;
                $result = self::set_discount_value($discount, $price, $cat_id);
                if (is_array($result) && $result['status'] == 'Ok') {
                    if (array_key_exists($cat_id, $discount_value_array)) {
                        if ($result['discount_value'] > $discount_value_array[$cat_id]) {
                            $discount_value_array[$cat_id] = $result['discount_value'];
                        }
                    } else {
                        $discount_value_array[$cat_id] = $result['discount_value'];
                    }
                }

            }
        }
        foreach ($discounts as $discount) {
            $cat_id = $discount->cat_id;
            if ($cat_id==0){
                $price = Session::get('final_price', 0);
                $result = self::set_discount_value($discount, $product_price2, $cat_id);
                if (is_array($result) && $result['status'] == 'Ok') {
                    if (array_key_exists($cat_id, $discount_value_array)) {
                        if ($result['discount_value'] > $discount_value_array[$cat_id]) {
                            $discount_value_array[$cat_id] = $result['discount_value'];
                        }
                    } else {
                        $discount_value_array[$cat_id] = $result['discount_value'];
                    }
                }
            }
        }
        $cart_final_price = self::get_cart_final_price();
        $discount_value = 0;
        foreach ($discount_value_array as $key => $value) {
                $discount_value += $value;
        }
        if ($discount_value > 0) {
            $cart_final_price = abs($cart_final_price - $discount_value);
            if (Session::get('gift_value',0)>0){
                $cart_final_price=$cart_final_price-Session::get('gift_value',0);
            }
            Session::put('discount_value', $discount_value);
            Session::put('discount_code', $discounts[0]->code);
            return [
                'status' => 'Ok',
                'discount_value' => replace_number(number_format($discount_value)) . ' ??????????',
                'cart_final_price' => replace_number(number_format($cart_final_price)) . ' ??????????',
            ];
        } else {
            return '?????????? ?????????????? ???? ?????? ???? ?????????? ???????? ?????????????? ?????????? ???? ?????? ???????? ?????? ???????? ??????????.';

        }
    }

    private static function set_discount_value($discount, $price, $cat_id)
    {
        if ($price > 0) {
            if ($price >= $discount->amount) {
                $discount_value = 0;
                if (!empty($discount->amount_discount)) {
                    $discount_value = $discount->amount_discount;

                } elseif (!empty($discount->amount_percent)) {
                    $discount_value = ($discount->amount_percent * $price) / 100;
                }
                return [
                    'status' => 'Ok',
                    'discount_value' => $discount_value,
                ];
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }
    }
    public static function get_cart_final_price(){
        $cart_final_price = Session::get('final_price', 0);
//        if (Session::get('discount_value',0)>0){
//            $cart_final_price=$cart_final_price+Session::get('discount_value',0);
//        }
//        if (Session::get('gift_value',0)>0){
//            $cart_final_price=$cart_final_price+Session::get('gift_value',0);
//        }
        return $cart_final_price;
    }
}

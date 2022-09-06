<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Cart extends Model
{
    protected static $cart_product_number = [];

    public static function addCart($data)
    {
        Session::forget('final_price');
        Session::forget('discount_value');
        Session::forget('gift_value');


        $productId = $data->get('product_id', 0);
        $colorId = $data->get('color_id', 0);
        $warrantyId = $data->get('warranty_id', 0);
        $s_c = $warrantyId . '_' . $colorId;
        $cart = Session::get('cart', []);
        if (array_key_exists($productId, $cart)) {
            $product_data = $cart[$productId]['product_data'];
            if (array_key_exists($s_c, $product_data)) {
                $count = $cart[$productId]['product_data'][$s_c] + 1;
                if (self::check($productId, $colorId, $warrantyId, $count)) {
                    $cart[$productId]['product_data'][$s_c]++;
                }
            } else {
                $cart[$productId]['product_data'][$s_c] = 1;
            }
        } else {
            $cart[$productId] = [
                'product_data' => [$s_c => 1]
            ];
        }
        Session::put('cart', $cart);
        return Session::get('cart', []);
    }

    public static function check($productId, $colorId, $warrantyId, $count)
    {
        $ProductWarranty = ProductWarranty::where([
            'product_id' => $productId,
            'warranty_id' => $warrantyId,
            'color_id' => $colorId,
        ])->first();
        if ($ProductWarranty && $ProductWarranty->product_number >= $count && $ProductWarranty->product_number_cart >= $count) {
            return true;
        } else {
            return false;
        }
    }

    public static function getCartData($type = 'cart')
    {
        $cart = Session::get('cart', []);
        $product_id = [];
        $color_id = [];
        $warranty_id = [];
        $cart_product_number = [];
        $data = [];
        $i = 0;
        foreach ($cart as $key => $value) {
            foreach ($value['product_data'] as $key2 => $value2) {
                $a = explode("_", $key2);
                if (sizeof($a) == 2) {
                    $product_id[$key] = $key;
                    $color_id[$a[1]] = $a[1];
                    $warranty_id[$a[0]] = $a[0];
                    $row = ProductWarranty::where([
                        'product_id' => $key,
                        'color_id' => $a[1],
                        'warranty_id' => $a[0]
                    ])->first();
                    if ($row) {
                        $data[$i] = $row;
                        $i++;
                        $k = $key . "_" . $a[1] . "_" . $a[0];
                        $cart_product_number[$k] = $value2;
                    }
                }
            }
        }
        $products = Product::whereIn('id', $product_id)->select(['id', 'title', 'image_url','cat_id'])->get();
        $colors = Color::whereIn('id', $color_id)->get();
        $warranties = Warranty::whereIn('id', $warranty_id)->get();
        $cart_data = [];
        $total_price = 0;
        $cart_price = 0;
        $j = 0;
        foreach ($data as $k => $v) {
            $product = getCartProductData($products, $v->product_id);
            $color = getCartProductData($colors, $v->color_id);
            $warranty = getCartProductData($warranties, $v->warranty_id);
            $n = $v->product_id . '_' . $v->color_id . '_' . $v->warranty_id;
            $product_number = array_key_exists($n, $cart_product_number) ? $cart_product_number[$n] : 0;

            if ($product && $warranty && $product_number > 0) {
                $cart_data['product'][$j]['product_id'] = $product->id;
                $cart_data['product'][$j]['product_title'] = $product->title;
                $cart_data['product'][$j]['cat_id'] = $product->cat_id;
                $cart_data['product'][$j]['product_image_url'] = $product->image_url;
                $cart_data['product'][$j]['warranty_name'] = $warranty->name;
                $cart_data['product'][$j]['warranty_id'] = $warranty->id;
                $cart_data['product'][$j]['send_day'] = $v->send_time;
                $cart_data['product'][$j]['product_warranty_id'] = $v->id;
                $cart_data['product'][$j]['seller_id'] = $v->seller_id;
                if ($color) {
                    $cart_data['product'][$j]['color_name'] = $color->name;
                    $cart_data['product'][$j]['color_code'] = $color->code;
                    $cart_data['product'][$j]['color_id'] = $color->id;
                } else
                    $cart_data['product'][$j]['color_id'] = 0;

                $cart_data['product'][$j]['price1'] = $product_number * $v->price1;// einga
                if ($type == 'cart') {
                    $cart_data['product'][$j]['price2'] = replace_number(number_format($product_number * $v->price2));
                } else {
                    $cart_data['product'][$j]['price2'] = $product_number * $v->price2;
                }
                $cart_data['product'][$j]['product_number_cart'] = $v->product_number_cart;
                $cart_data['product'][$j]['product_count'] = $product_number;
                $total_price += $product_number * $v->price1;
                $cart_price += $product_number * $v->price2;
                $j++;
            }
        }
        $discount = $total_price - $cart_price;
        Session::put('total_product_price',$total_price);
        Session::put('final_price',$cart_price);
        $cart_data['total_price'] = replace_number(number_format($total_price));
        $cart_data['cart_price'] = replace_number(number_format($cart_price));
        $cart_data['discount'] = $discount > 0 ? replace_number(number_format($discount)) : 0;
        $cart_data['product_count'] = $j;
        return $cart_data;
    }

    public static function removeProduct($request)
    {
        $product_id = $request->get('product_id', 0);
        $warranty_id = $request->get('warranty_id', 0);
        $color_id = $request->get('color_id', 0);
        $cart = Session::get('cart');
        if (array_key_exists($product_id, $cart)) {
            $a = $cart[$product_id]['product_data'];
            $childKey = $warranty_id . '_' . $color_id;
            if (array_key_exists($childKey, $a)) {
                Session::forget('final_price');
                Session::forget('discount_value');
                Session::forget('gift_value');
                unset($cart[$product_id]['product_data'][$childKey]);
                if (empty($cart[$product_id]['product_data'])) {
                    unset($cart[$product_id]);
                }
                if (empty($cart)) {
                    Session::forget('cart');
                } else {
                    Session::put('cart', $cart);
                }
                return self::getCartData();
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }
    }

    public static function changeProductCart($request)
    {
        $product_id = $request->get('product_id', 0);
        $warranty_id = $request->get('warranty_id', 0);
        $color_id = $request->get('color_id', 0);
        $product_count = $request->get('product_count', 1);
        settype($product_count, 'integer');
        $cart = Session::get('cart');
        if (array_key_exists($product_id, $cart)) {
            $a = $cart[$product_id]['product_data'];
            $childKey = $warranty_id . '_' . $color_id;
            if (array_key_exists($childKey, $a)) {
                if (self::check($product_id, $color_id, $warranty_id, $product_count) && $product_count > 0) {
                    $cart[$product_id]['product_data'][$childKey] = $product_count;
                    Session::put('cart', $cart);
                    return self::getCartData();
                } else {
                    return 'error';
                }
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }
    }

    public static function getProductCount()
    {
        $count = 0;
        $cart = Session::get('cart', []);
        foreach ($cart as $value) {
            $count += sizeof($value['product_data']);
        }
        return $count;
    }
}
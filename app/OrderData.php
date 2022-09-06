<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderData extends Model
{
    protected $OrderInfo;
    protected $productRow;
    protected $order_row_amount = [];
    protected $order_row_products = [];
    protected $array_product_id = [];
    protected $array_warranty_id = [];
    protected $array_color_id = [];
    protected $row_data = [];
    protected $user_id = 0;
    protected $order_id = 0;
    protected $check_gift_cart = 'no';

    public function __construct($getOrderInfo, $getProductRow, $user_id, $checkGiftCart='no')
    {
        $this->user_id = $user_id;
        $this->check_gift_cart = $checkGiftCart;
        $this->OrderInfo = $getOrderInfo;
        $this->productRow = $getProductRow;
    }

    public function getData($id = 0)
    {
        foreach ($this->OrderInfo as $key => $info) {
            if (($id > 0 && $info->id = $id) || $id == 0) {
                $this->order_row_amount[$info->id] = $info->send_order_amount;
                $product_id = explode('-', $info->product_id);
                $color_id = explode('-', $info->colors_id);
                $warranty_id = explode('-', $info->warranty_id);
                foreach ($product_id as $key => $value) {
                    if (!empty($value)) {
                        $this->getProductDataOfList($info, $this->productRow, $value, $color_id[$key], $warranty_id[$key]);
                    }
                }
            }
        }

        $this->getProductData();
        return [
            'order_row_amount' => $this->order_row_amount,
            'row_data' => $this->row_data,
        ];
    }

    public function getProductDataOfList($info, $product, $product_id, $color_id, $warranty_id)
    {
        foreach ($product as $key => $value) {
            if ($value->product_id == $product_id && $value->warranty_id == $warranty_id) {
                $amount = $value->product_price2 * $value->product_count;
                $p = array_key_exists($info->id, $this->order_row_amount) ? $this->order_row_amount[$info->id] : 0;
                $this->order_row_amount[$info->id] = $p + $amount;
                $size = array_key_exists($info->id, $this->order_row_products) ? sizeof($this->order_row_products[$info->id]) : 0;
                $this->order_row_products[$info->id][$size] = $value;
                $this->array_product_id[$value->product_id] = $value->product_id;
                $this->array_warranty_id[$value->warranty_id] = $value->warranty_id;
                $this->array_color_id[$value->color_id] = $value->color_id;
            }
        }
    }

    private function getProductData()
    {
        $products = Product::whereIn('id', $this->array_product_id)->select(['id', 'title', 'image_url', 'use_for_gift_cart'])->get();
        $colors = Color::whereIn('id', $this->array_color_id)->get();
        $warranties = Warranty::whereIn('id', $this->array_warranty_id)->get();
        $j = 0;
        foreach ($this->order_row_products as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $product = getCartProductData($products, $value2->product_id);
                $color = getCartProductData($colors, $value2->color_id);
                $warranty = getCartProductData($warranties, $value2->warranty_id);
                if ($product && $warranty) {
                    if ($this->check_gift_cart == 'yes')
                        checkGiftCart($product, $this->user_id, $value2->product_price2, $this->OrderInfo[0]->order_id);
                    $this->row_data[$key][$j] = $value2;
                    $this->row_data[$key][$j]['product_id'] = $product->id;
                    $this->row_data[$key][$j]['title'] = $product->title;
                    $this->row_data[$key][$j]['image_url'] = $product->image_url;
                    $this->row_data[$key][$j]['warranty_name'] = $warranty->name;
                    if ($color) {
                        $this->row_data[$key][$j]['color_name'] = $color->name;
                        $this->row_data[$key][$j]['color_code'] = $color->code;
                        $this->row_data[$key][$j]['color_id'] = $color->id;
                    } else
                        $this->row_data[$key][$j]['color_id'] = 0;
                    $this->row_data[$key][$j]['product_count'] = $value2->product_count;
                    $this->row_data[$key][$j]['product_price1'] = $value2->product_price1;
                    $this->row_data[$key][$j]['product_price2'] = $value2->product_price2;
                    $j++;
                }

            }
        }
    }
}

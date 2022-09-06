<?php

use App\Category;
use App\Lib\Jdf;
use App\ProductPrice;
use App\ProductWarranty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;


function get_url($string)
{
    $url = str_replace('-', ' ', $string);
    $url = str_replace('/', ' ', $url);
    $url = preg_replace('/\s+/', '-', $url);

    return $url;
}

function upload_file($request, $name, $dir, $pix = '')
{
    if ($request->hasFile($name)) {
        $file_name = $pix . time() . '.' . $request->file($name)->getClientOriginalExtension();
        if ($request->file($name)->move('files/uploads/' . $dir, $file_name))
            return $file_name;
        else
            return null;
    } else {
        return null;
    }

}

function replace_number($number)
{
    $number = str_replace("0", "۰", $number);
    $number = str_replace("1", "۱", $number);
    $number = str_replace("2", "۲", $number);
    $number = str_replace("3", "۳", $number);
    $number = str_replace("4", "۴", $number);
    $number = str_replace("5", "۵", $number);
    $number = str_replace("6", "۶", $number);
    $number = str_replace("7", "۷", $number);
    $number = str_replace("8", "۸", $number);
    $number = str_replace("9", "۹", $number);
    return $number;
}

function replace_number2($number)
{
    $number = str_replace("۰", "0", $number);
    $number = str_replace("۱", "1", $number);
    $number = str_replace("۲", "2", $number);
    $number = str_replace("۳", "3", $number);
    $number = str_replace("۴", "4", $number);
    $number = str_replace("۵", "5", $number);
    $number = str_replace("۶", "6", $number);
    $number = str_replace("۷", "7", $number);
    $number = str_replace("۸", "8", $number);
    $number = str_replace("۹", "9", $number);
    return $number;
}

function remove_file($filename, $dir)
{
    if ($filename != null && file_exists('files/uploads/' . $dir . '/' . $filename))
        unlink('files/uploads/' . $dir . $filename);
}

function isTrashed($request)
{
    if (array_key_exists('trashed', $request) && $request['trashed'] == true)
        return true;
    else
        return false;
}

function create_paginate_url($string, $text)
{
    if ($string == '?')
        $string = $string . $text;
    else
        $string = $string . '&' . $text;

    return $string;
}

function create_crud_route($route, $controller, $except = ['show'])
{
    Route::resource("$route", "$controller")->except($except);
    Route::post("$route/remove_items", "$controller@remove_items");
    Route::post("$route/restore_items", "$controller@restore_items");
    Route::post("$route/{id}", "$controller@restore");
}

function create_fit_pic($picUrl, $picName)
{
    $thum = Image::make($picUrl);
    $thum->resize(350, 350);
    $thum->save('files/uploads/thumbnails/' . $picName);
}

function update_product_price($product)
{
    $productWarranty = ProductWarranty::where('product_id', $product->id)->where('product_number', '>', 0)->orderBy('price2', 'ASC')->first();
    $productWarranty2 = ProductWarranty::where('product_id', $product->id)->orderBy('send_time', 'ASC')->first();
    if ($productWarranty) {
        $product->price = $productWarranty->price2;
        if ($productWarranty->price1 > $productWarranty->price2) {
            $discount_price = $productWarranty->price1 - $productWarranty->price2;
            $product->discount_price = $discount_price;
        } else
            $product->discount_price = 0;

        $product->status = 1;
        $product->ready_to_shipment = $productWarranty2->send_time;
        $product->update();
    } else {
        $product->discount_price = 0;
        $product->status = 0;
        $product->update();
    }

}

function add_min_product_price($productWarranty)
{
    $jdf = new Jdf();
    $year = $jdf->tr_num($jdf->jdate('Y'));
    $mount = $jdf->tr_num($jdf->jdate('n'));
    $day = $jdf->tr_num($jdf->jdate('j'));
    $has_row = DB::table('product_price')
        ->where(['Year' => $year, 'mount' => $mount, 'day' => $day, 'color_id' => $productWarranty->color_id, 'product_id' => $productWarranty->product_id])->first();
    if ($has_row) {
        if ($has_row->price > $productWarranty->price2 || $has_row->price == 0) {
            DB::table('product_price')
                ->where(['Year' => $year, 'mount' => $mount, 'day' => $day, 'color_id' => $productWarranty->color_id, 'product_id' => $productWarranty->product_id])
                ->update([
                    'price' => $productWarranty->price2,
                    'warranty_id' => $productWarranty->warranty_id
                ]);
        }
    } else {
        DB::table('product_price')
            ->insert([
                'Year' => $year,
                'mount' => $mount,
                'day' => $day,
                'color_id' => $productWarranty->color_id,
                'product_id' => $productWarranty->product_id,
                'warranty_id' => $productWarranty->warranty_id,
                'price' => $productWarranty->price2,
                'time' => time()
            ]);
    }
}

function check_has_product_warranty($productWarranty)
{
    $jdf = new Jdf();
    $year = $jdf->tr_num($jdf->jdate('Y'));
    $mount = $jdf->tr_num($jdf->jdate('n'));
    $day = $jdf->tr_num($jdf->jdate('j'));

    $row = ProductWarranty::where(['product_id' => $productWarranty->product_id, 'color_id' => $productWarranty->color_id])
        ->where('product_number', '>', '0')
        ->orderBy('price2', 'ASC')->first();
    $price = $row ? $row->price2 : 0;
    $warranty_id = $row ? $row->warranty_id : 0;
    $has_row = ProductPrice::where(['Year' => $year, 'mount' => $mount, 'day' => $day, 'color_id' => $productWarranty->color_id, 'product_id' => $productWarranty->product_id])
        ->first();
    if ($has_row) {
        $has_row->price = $price;
        $has_row->warranty_id = $warranty_id;
        $has_row->update();
    } else {
        ProductPrice::insert([
            'Year' => $year,
            'mount' => $mount,
            'day' => $day,
            'color_id' => $productWarranty->color_id,
            'product_id' => $productWarranty->product_id,
            'warranty_id' => $warranty_id,
            'price' => $price,
            'time' => time()
        ]);
    }
}

function getShowValue($request, $key, $key1)
{
    $checkBoxInputs = $request->get('check_box_input', []);
    if (!empty($checkBoxInputs)) {
        if (array_key_exists($key, $checkBoxInputs)) {
            if (array_key_exists($key1, $checkBoxInputs[$key])) {
                return 1;
            }
        }
    }
    return 0;

}

function is_selected_filter($list, $filter_id)
{
    $result = false;
    foreach ($list as $value) {
        if ($value->filter_value == $filter_id) {
            $result = true;
        }
    }
    return $result;
}

function getFilterArray($list)
{
    $array = [];
    foreach ($list as $key => $value) {
        $array[$value->item_id] = $key;
    }
    return $array;
}


function getFilterItemValue($filter_id, $product_filter)
{
    $string = '';
    foreach ($product_filter as $key => $value) {
        if ($value == $filter_id) {
            $string .= '@' . $key;

        }
    }
    return $string;
}

function get_show_category_count($cat)
{
    $n = 0;
    foreach ($cat as $value) {
        if ($value->notShow == 0)
            $n++;
    }


    return $n;
}

function getCatList()
{

    $data = cache('catList');
    if ($data) {
        View::share('catList', $data);
    } else {
        $catList = Category::with('getChild.getChild.getChild')->where('parent_id', 0)->get();
        $minutes = 30 * 24 * 60 * 60;
        cache()->put('catList', $catList, $minutes);
        View::share('catList', $catList);
    }

}

function get_cat_url($cat)
{
    if (!empty($cat->search_url)) {
        return $cat->search_url;
    } else {
        return 'search/' . $cat->url;
    }
}

function getTimestamp($date, $type)
{
    $jdf = new Jdf();
    $time = 0;
    $e = explode('/', $date);
    if (sizeof($e) == 3) {
        $y = $e[0];
        $m = $e[1];
        $d = $e[2];
        if ($type == 'first') {
            $time = $jdf->jmktime(0, 0, 0, $m, $d, $y);
        } else {
            $time = $jdf->jmktime(23, 59, 59, $m, $d, $y);
        }
    }
    return $time;

}

function check_has_color_in_product_warranty_list($productWarranties, $color_id)
{
    $r = false;
    foreach ($productWarranties as $key => $productWarranty) {
        if ($productWarranty->color_id == $color_id) {
            $r = true;
        }
    }
    return $r;
}

function get_first_color_id($productWarranties, $color_id)
{
    if (sizeof($productWarranties) > 0) {
        if ($productWarranties[0]->color_id == $color_id)
            return true;

        else
            return false;
    }
}

function getCartProductData($array, $id)
{
    foreach ($array as $key => $value) {
        if ($value->id == $id) {
            return $value;
        }
    }
}

function set_order_product_status($order_info, $status)
{
    $warranty_id = explode('-', $order_info->warranty_id);
    $products_id = explode('-', $order_info->product_id);
    $colors_id = explode('-', $order_info->colors_id);
    foreach ($products_id as $key => $value) {
        if (!empty($value)) {
            DB::table('order_products')->where(['order_id' => $order_info->order_id, 'product_id' => $value, 'warranty_id' => $warranty_id[$key], 'color_id' => $colors_id[$key]])
                ->update(['send_status' => $status]);
        }
    }
}

function getOrderProductCount($product_ids)
{
    $i = 0;
    $ids = explode('-', $product_ids);
    foreach ($ids as $id) {
        $i++;
    }
    return $i;
}

function checkGiftCart($product, $user_id, $creditCart, $order_id)
{
    if ($product->use_for_gift_cart == 'yes') {
        $code = 'digiGift-' . rand(99, 9990) . $user_id . rand(9, 99);
        $giftCart = new \App\GiftCart();
        $giftCart->user_id = $user_id;
        $giftCart->order_id = $order_id;
        $giftCart->credit_cart = $creditCart;
        $giftCart->credit_used = 0;
        $giftCart->code = $code;
        $giftCart->save();
    }
}

function set_cat_brand($oldData, $product)
{
    if ($oldData) {
        if ($oldData['cat_id'] != $product->cat_id) {
            check_has_cat_brand($product->cat_id, $product->brand_id);
            remove_cat_brand($oldData['cat_id'], $oldData['brand_id']);
        } elseif ($oldData['brand_id'] != $product->brand_id) {
            check_has_cat_brand($product->cat_id, $product->brand_id);
            remove_cat_brand($oldData['cat_id'], $oldData['brand_id']);
        }
    } else {
        check_has_cat_brand($product->cat_id, $product->brand_id);
    }

}

function add_cat_brand($cat_id, $brand_id)
{
    $catBrand = new \App\CatBrand();
    $catBrand->cat_id = $cat_id;
    $catBrand->brand_id = $brand_id;
    $catBrand->product_count = 1;
    $catBrand->save();
}

function check_has_cat_brand($cat_id, $brand_id)
{
    $row = \App\CatBrand::where(['cat_id' => $cat_id, 'brand_id' => $brand_id])->first();
    if ($row) {
        $row->product_count += 1;
        $row->update();
    } else {
        add_cat_brand($cat_id, $brand_id);
    }
}

function remove_cat_brand($cat_id, $brand_id)
{
    $row = \App\CatBrand::where(['cat_id' => $cat_id, 'brand_id' => $brand_id])->first();
    if ($row && $row->product_count > 2) {
        $row->product_count -= 1;
        $row->update();
    } else {
        if ($row) {
            $row->delete();
        }
    }
}

function return_id_product($ids)
{
    $id = [];
    $i = 0;
    if (is_array($ids)) {
        foreach ($ids as $v) {
            if ($v != null) {
                $id[$i] = explode('-', $v)[1];
                $i++;
            }
        }
    }
    return $id;
}

function get_item_value($key, $products, $item_id)
{
//    dd($item_id,$products[$key]->getItemValue);
    $string = '';
    if (sizeof($products) > $key) {
        foreach ($products[$key]->getItemValue as $item) {
            if ($item_id == $item->item_id) {
                $string .= $item->item_value . '<br>';
            }
        }
    }
    return $string;
}

function get_comment_item($array)
{
    $string = '';
    foreach ($array as $key => $value) {
        $string .= $value . '|[@#]';
    }
    return $string;
}

function get_comment_order_id($product_id, $user_id)
{
    $order_id = 0;
    define('product_id', $product_id);
    $order = \App\Order::whereHas('getOrderProduct', function (\Illuminate\Database\Eloquent\Builder $query) {
        $query->where('product_id', product_id);
    })->where(['user_id' => $user_id, 'pay_status' => 'Ok'])->select('id')->first();
    return $order_id;
}

function addScore($array_score, $comment_id, $product_id)
{
    if (sizeof($array_score) == 6) {
        $commentScore = new \App\CommentScore();
        $commentScore->comment_id = $comment_id;
        $commentScore->product_id = $product_id;
        $commentScore->score1 = $array_score[0];
        $commentScore->score2 = $array_score[1];
        $commentScore->score3 = $array_score[2];
        $commentScore->score4 = $array_score[3];
        $commentScore->score5 = $array_score[4];
        $commentScore->score6 = $array_score[5];
        $commentScore->saveOrFail();
    }
}

function getScoreItem($score, $typeScore)
{

    return $scores = [
        ['label' => 'کیفیت ساخت :', 'value' => $score->score1, 'type' => getScoreType($score->score1, $typeScore)],
        ['label' => 'نوآوری :', 'value' => $score->score2, 'type' => getScoreType($score->score2, $typeScore)],
        ['label' => 'سهولت استفاده :', 'value' => $score->score3, 'type' => getScoreType($score->score3, $typeScore)],
        ['label' => 'سهولت طراحی و ظاهر :', 'value' => $score->score4, 'type' => getScoreType($score->score4, $typeScore)],
        ['label' => 'امکانات و قابلیت ها :', 'value' => $score->score5, 'type' => getScoreType($score->score5, $typeScore)],
        ['label' => 'ارزش خرید به نسبت قیمت :', 'value' => $score->score6, 'type' => getScoreType($score->score6, $typeScore)],
    ];
}

function getScoreType($score, $typeScore)
{
    if (array_key_exists($score,$typeScore)) {
        return $typeScore[$score];
    } else {
        return '';
    }
}


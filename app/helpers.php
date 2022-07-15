<?php

use App\Category;
use App\Lib\Jdf;
use App\ProductPrice;
use App\ProductWarranty;
use Illuminate\Support\Facades\DB;
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

function create_crud_route($route, $controller, $show = false)
{
    if ($show == false)
        Route::resource("$route", "$controller")->except(['show']);
    else
        Route::resource("$route", "$controller");

    Route::resource("$route", "$controller")->except(['show']);
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
    if ($productWarranty) {
        $product->price = $productWarranty->price2;
        if ($productWarranty->price1 > $productWarranty->price2) {
            $discount_price = $productWarranty->price1 - $productWarranty->price2;
            $product->discount_price = $discount_price;
        } else
            $product->discount_price = 0;

        $product->status = 1;
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
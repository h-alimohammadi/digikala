<?php

use App\Lib\Jdf;
use App\ProductPrice;
use App\ProductWarranty;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

function get_url($string)
{
    $url = str_replace('-', ' ', $string);
    $url = str_replace('/', ' ', $url);
    $url = preg_replace('/\s+/', '-', $url);

    return $url;
}

function upload_file($request, $name, $dir, $pix='')
{
    if ($request->hasFile($name)) {
        $file_name =$pix. time() . '.' . $request->file($name)->getClientOriginalExtension();
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
        $product->status = 1;
        $product->update();
    } else {
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
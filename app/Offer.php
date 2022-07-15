<?php

namespace App;

use App\Jobs\IncredibleOffers;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Validator;

class Offer extends Model
{
    public function add($request, $productWarranty)
    {
        $validator =Validator::make($request->all(), [
            'price1'=>'required|numeric',
            'price2'=>'required|numeric',
            'product_number'=>'required|numeric',
            'product_number_cart'=>'required|numeric',
            'date1'=>'required',
            'date2'=>'required',
        ], [], [
            'price1' => 'هزینه محصول',
            'price2' => 'هزینه محصول برای فروش',
            'product_number' => 'تعداد موجودی محصول',
            'product_number_cart' => 'تعداد قابل سفارش در سبد خرید',
            'date1'=>'تاریخ شروع',
            'date2'=>'تاریخ پایان',
        ]);
        if ($validator->fails()){
            return $validator->errors();
        }
        $date1 = $request->get('date1');
        $date2 = $request->get('date2');
        $offers_first_time = getTimestamp($date1, 'first');
        $offers_last_time = getTimestamp($date2, 'last');
        $row = DB::table('old_price')->where('productWarranty_id', $productWarranty->id)->first();
        if (!$row) {
            $this->addNewPriceRow($request, $productWarranty);
        } else {
            $this->updatePriceRow($row, $request, $productWarranty);
        }

        $productWarranty->offers = 1;
        $productWarranty->offers_first_date = $date1;
        $productWarranty->offers_last_date = $date2;
        $productWarranty->offers_first_time = $offers_first_time;
        $productWarranty->offers_last_time = $offers_last_time;

        if ($productWarranty->update($request->all())) {
            $second = $offers_last_time-time()+1;
            IncredibleOffers::dispatch($productWarranty->id)->delay(now()->addSecond($second));
            $product = Product::where('id', $productWarranty->product_id)->select('id', 'price', 'status')->first();
            add_min_product_price($productWarranty);
            update_product_price($product);
            return 'Ok';
        } else
            return ['error'=>true];

    }

    public function addNewPriceRow($request, $productWarranty)
    {
        $n = $productWarranty->product_number - $request->get('product_number');
        if ($n < 0)
            $n = 0;

        DB::table('old_price')
            ->insertGetId([
                'productWarranty_id' => $productWarranty->id,
                'price1' => $productWarranty->price1,
                'price2' => $productWarranty->price2,
                'product_number' => $n,
                'product_number_cart' => $productWarranty->product_number_cart,
                'number_product_sale' => $request->get('product_number'),
            ]);
    }

    private function updatePriceRow($row, $request, $productWarranty)
    {
        $n = $row->product_number;
        if ($row->number_product_sale > $request->get('product_number')) {
            $n = $n + ($row->number_product_sale - $request->get('product_number'));
        } else {
            $n = $n - ($request->get('product_number') - $row->number_product_sale);
        }
        DB::table('old_price')->where('productWarranty_id', $productWarranty->id)
            ->update(['number_product_sale' => $request->get('product_number'), 'product_number' => $n]);

    }

    public function remove($productWarranty)
    {
        $row = DB::table('old_price')->where('productWarranty_id', $productWarranty->id)->first();
        if ($row) {
            $productWarranty->offers = 0;
            $productWarranty->price1 = $row->price1;
            $productWarranty->price2 = $row->price2;
            if ($row->product_number > 0) {
                $productWarranty->product_number = $productWarranty->product_number + $row->product_number;
            }
            if ($productWarranty->update()) {
                if (DB::table('old_price')->where('productWarranty_id', $productWarranty->id)->delete()) {
                    $product = Product::where('id', $productWarranty->product_id)->select('id', 'price', 'status')->first();
                    add_min_product_price($productWarranty);
                    update_product_price($product);
                    return $productWarranty;
                } else
                    return 'error';
            } else {
                return 'error';

            }
        } else {
            return 'error';
        }

    }
}
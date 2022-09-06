<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    protected $table = 'order_infos';
    protected $fillable = ['order_id', 'delivery_order_interval', 'send_order_amount', 'product_id', 'warranty_id', 'send_status', 'order_send_time'];

    public static function getData($request, $status = 0, $order = 'ASC')
    {
        $string = '?';
        $submission = self::orderBy('order_send_time',$order);
        if (isTrashed($request)) {
            $submission = $submission->onlyTrashed();
            create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('submission_id', $request) && $request['submission_id'] != '') {
            $submission_id = replace_number2($request['submission_id']);
            $submission = $submission->where('id', $submission_id);
            create_paginate_url($string, 'submission_id=' . $submission_id);
        }
        if ($status >= 1) {
            $submission = $submission->where('send_status', $status);
        }
        $submission = $submission->orderBy('id','DESC')->paginate(15);
        $submission->withPath($string);
        return $submission;
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }
}

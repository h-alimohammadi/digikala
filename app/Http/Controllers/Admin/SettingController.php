<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function sendOrderPrice(SettingRequest $request)
    {
        $setting = new Setting();
        if ($request->isMethod('post')) {
            $data = $setting->setData($request->all());
        } else {
            $data = $setting->getData(['send_time', 'send_price', 'min_order_price']);
        }
//        return $data;
        return view('setting.send_order_price', compact('data'));
    }
}

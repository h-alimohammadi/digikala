<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SliderRequest;
use App\Slider;
use Illuminate\Http\Request;

class SliderController extends CustomController
{
    protected $model = 'Slider';
    protected $title = 'محصول';
    protected $route_params = 'slider';

    public function index(Request $request)
    {
        $sliders = Slider::getData($request->all());
        $trashed_slider_count = Slider::onlyTrashed()->count();
        return view('slider.index', compact('sliders', 'trashed_slider_count', 'request'));
    }

    public function create()
    {
        return view('slider.create');
    }

    public function store(SliderRequest $request)
    {
        $slider = new Slider($request->all());
        $image_url = upload_file($request, 'pic', 'slider', 'desktop');
        $mobile_image_url = upload_file($request, 'mobile_pic', 'slider/mobile', 'mobile');
        $slider->image_url = $image_url;
        $slider->mobile_image_url = $mobile_image_url;
        $slider->save();
        return redirect('admin/slider')->with('message', 'ثبت اسلایدر با موفقیت انجام شد.');
    }

    public function edit(Slider $slider)
    {
        return view('slider.edit', compact('slider'));
    }

    public function update(Slider $slider, SliderRequest $request)
    {
        $data = $request->all();
        $image_url = upload_file($request, 'pic', 'slider', 'desktop');
        $mobile_image_url = upload_file($request, 'mobile_pic', 'slider/mobile', 'mobile');
        if ($image_url != null) {
            remove_file($slider->image_url,'slider/');
            $data['image_url'] = $image_url;
        }
        if ($mobile_image_url != null) {
            remove_file( $slider->mobile_image_url,'slider/mobile/');
            $data['mobile_image_url'] = $mobile_image_url;

        }
        $slider->update($data);
        return redirect('admin/slider')->with('message', 'ویرایش اسلایدر با موفقیت انجام شد.');
    }


}

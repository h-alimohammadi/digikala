@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت اسلایدر ها','url'=>url('admin/slider')],
   ['title'=>'ویرایش اسلایدر','url'=>url('admin/slider/'.$slider->id.'/edit')]
   ]])
    <div class="panel">
        <div class="header">ویرایش اسلایدر - ( {{$slider->title}} ) </div>
        <div class="panel_content">
            {!! Form::Model($slider,['url'=>'admin/slider/'.$slider->id , 'files' => true]) !!}
            @method('PUT')
            <div class="form-group">
                {!! Form::label('title','عنوان اسلایدر') !!}
                {!! Form::text('title',old('title'),['class'=>'form-control']) !!}
                @if($errors->has('title'))
                    <span class="has_error">{{$errors->first('title')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('url','آدرس (url)') !!}
                {!! Form::text('url',old('url'),['class'=>'form-control left total_width_input']) !!}
                @if($errors->has('url'))
                    <span class="has_error">{{$errors->first('url')}}</span>
                @endif
            </div>
            <div class="form-group">
                <input type="file" name="pic" onchange="loadFile(event)" id="pic" class="d-none">
                {!! Form::label('pic','انتخاب تصویر اسلایدر :') !!}
                <img src="{{$slider->image_url != null ? asset('files/uploads/slider/'.$slider->image_url) :asset('files/images/pic_1.jpg')}}" onclick="select_file()" id="output"  class="slider_img">
                @if($errors->has('pic'))
                    <span class="has_error">{{$errors->first('pic')}}</span>
                @endif
            </div>
            <div class="form-group">
                <input type="file" name="mobile_pic" onchange="loadFile2(event)" id="mobile_pic" class="d-none">
                {!! Form::label('mobile_pic','انتخاب تصویر موبایل برای اسلایدر :') !!}
                <img src="{{$slider->mobile_image_url != null ? asset('files/uploads/slider/mobile/'.$slider->mobile_image_url) :asset('files/images/pic_1.jpg')}}" onclick="select_file2()" id="output2" class="slider_img">
            </div>
            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
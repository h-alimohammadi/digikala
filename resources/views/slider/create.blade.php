@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت اسلایدر ها','url'=>url('admin/slider')],
   ['title'=>'افزودن اسلایدر','url'=>url('admin/slider/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن اسلایدر جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/slider','files'=>true]) !!}
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
                <img src="{{asset('files/images/pic_1.jpg')}}" onclick="select_file()" id="output"  class="slider_img">
                @if($errors->has('pic'))
                    <span class="has_error">{{$errors->first('pic')}}</span>
                @endif
            </div>
            <div class="form-group">
                <input type="file" name="mobile_pic" onchange="loadFile2(event)" id="mobile_pic" class="d-none">
                {!! Form::label('mobile_pic','انتخاب تصویر موبایل برای اسلایدر :') !!}
                <img src="{{asset('files/images/pic_1.jpg')}}" onclick="select_file2()" id="output2" class="slider_img">
            </div>
            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
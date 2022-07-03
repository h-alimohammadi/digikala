@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت دسته ها','url'=>url('admin/category')],
   ['title'=>'افزودن دسته','url'=>url('admin/category/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن دسته جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/category','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('name','نام دسته') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('ename','نام لاتین') !!}
                {!! Form::text('ename',old('ename'),['class'=>'form-control']) !!}

            </div>
            <div class="form-group">
                {!! Form::label('search_url','url دسته') !!}
                {!! Form::text('search_url',old('search_url'),['class'=>'form-control']) !!}
                @if($errors->has('search_url'))
                    <span class="has_error">{{$errors->first('search_url')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('parent_id','دسته سردسته :') !!}
                {!! Form::select('parent_id',$parent_category,null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
            </div>
            <div class="form-group">
                <input type="file" name="img" onchange="loadFile(event)" id="pic" class="d-none">
                {!! Form::label('pic','انتخاب تصویر :') !!}
                <img src="{{asset('files/images/pic_1.jpg')}}" onclick="select_file()" width="150" id="output">
                @if($errors->has('img'))
                    <span class="has_error">{{$errors->first('img')}}</span>
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('notShow','عدم نمایش در لیست اصلی') !!}
                {!! Form::checkbox('notShow',false) !!}
            </div>
            <button class="btn btn-success">ثبت دسته</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
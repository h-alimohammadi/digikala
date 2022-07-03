@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت برند ها','url'=>url('admin/brand')],
   ['title'=>'افزودن برند','url'=>url('admin/brand/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن برند جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/brand','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('name','نام برند') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('ename','نام لاتین') !!}
                {!! Form::text('ename',old('ename'),['class'=>'form-control']) !!}
                @if($errors->has('ename'))
                    <span class="has_error">{{$errors->first('ename')}}</span>
                @endif

            </div>
            <div class="form-group">
                {!! Form::label('description','توضیحات :') !!}
                {!! Form::textarea('description',null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
            </div>

            <div class="form-group">
                <input type="file" name="icon" onchange="loadFile(event)" id="pic" class="d-none">
                {!! Form::label('pic','آیکن برند :') !!}
                <img src="{{asset('files/images/pic_1.jpg')}}" onclick="select_file()" width="150" id="output">
                @if($errors->has('img'))
                    <span class="has_error">{{$errors->first('img')}}</span>
                @endif
            </div>
            <button class="btn btn-success">ثبت دسته</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
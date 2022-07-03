@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت گارانتی ها','url'=>url('admin/warranty')],
   ['title'=>'افزودن گارانتی','url'=>url('admin/warranty/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن گارانتی جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/warranty','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('name','نام گارانتی') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <button class="btn btn-success">ثبت دسته</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
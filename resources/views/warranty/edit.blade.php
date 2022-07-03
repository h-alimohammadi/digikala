@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت گارانتی ها','url'=>url('admin/warranty')],
   ['title'=>'ویرایش گارانتی','url'=>url('admin/warranty/'.$warranty->id.'/edit')]
   ]])
    <div class="panel">
        <div class="header">ویرایش برند - ( {{$warranty->name}} ) </div>
        <div class="panel_content">
            {!! Form::Model($warranty,['url'=>'admin/warranty/'.$warranty->id , 'files' => true]) !!}
            @method('PATCH')
            <div class="form-group">
                {!! Form::label('name','نام گارانتی') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <button class="btn btn-primary">ویرایش گارانتی</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
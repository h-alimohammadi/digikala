@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت نقد و بررسی ها','url'=>url('admin/product/review?product_id='.$product->id)],
   ['title'=>'افزودن  نقد و بررسی','url'=>url('admin/product/review/create?product_id='.$product->id)]
   ]])
    <div class="panel">
        <div class="header">
            افزودن نقد و بررسی جدید ( {{$product->title}} )
        </div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/product/review?product_id='.$product->id]) !!}
            <div class="form-group">
                {!! Form::label('title','عنوان  نقد و بررسی') !!}
                {!! Form::text('title',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('title'))
                    <span class="has_error">{{$errors->first('title')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::textarea('tozihat',old('tozihat'),['class'=>'form-control','id'=>'ckeditor1']) !!}
                @if($errors->has('tozihat'))
                    <span class="has_error">{{$errors->first('tozihat')}}</span>
                @endif
            </div>

            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('ckeditor/ckeditor.js')}}" type="text/javascript"></script>
    <script>
        CKEDITOR.replace('ckeditor1', {
            language: 'fa',
            filebrowserUploadUrl: '{{ route("editor-upload", ["_token" => csrf_token()]) }}',
            filebrowserUploadMethod: 'form',
            allowedContent : true,
            extraAllowedContent : '*(*)',
        })
    </script>
@endsection
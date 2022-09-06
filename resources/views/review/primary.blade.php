@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت نقد و بررسی ها','url'=>url('admin/product/review?product_id='.$product->id)],
   ['title'=>'افزودن  توضیحات اولیه','url'=>url('admin/product/review/primary?product_id='.$product->id)]
   ]])
    <div class="panel">
        <div class="header">
            افزودن توضیحات اولیه ( {{$product->title}} )
        </div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/product/review/primary?product_id='.$product->id]) !!}
            <div class="form-group">
                {!! Form::textarea('tozihat',$tozihat,['class'=>'form-control ckeditor']) !!}
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
@endsection
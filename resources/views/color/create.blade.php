@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت رنگ ها','url'=>url('admin/color')],
   ['title'=>'افزودن رنگ','url'=>url('admin/color/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن رنگ جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/color','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('name','نام رنگ') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('code','کد رنگ') !!}
                {!! Form::text('code',old('code'),['class'=>'form-control jscolor']) !!}
                @if($errors->has('code'))
                    <span class="has_error">{{$errors->first('code')}}</span>
                @endif

            </div>
            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/jscolor.js')}}"></script>
@endsection
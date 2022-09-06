@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت استان ها','url'=>url('admin/province')],
   ['title'=>'افزودن استان','url'=>url('admin/province/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن برند جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/province','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('name','نام استان') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
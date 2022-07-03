@extends('layouts.admin')

@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت رنگ ها','url'=>url('admin/color')],
   ['title'=>'ویرایش رنگ','url'=>url('admin/color/'.$color->id.'/edit')]
   ]])
    <div class="panel">
        <div class="header">ویرایش رنگ - ( {{$color->name}} )</div>
        <div class="panel_content">
            {!! Form::Model($color,['url'=>'admin/color/'.$color->id , 'files' => true]) !!}
            @method('PATCH')
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
            <button class="btn btn-primary">ویرایش رنگ</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/jscolor.js')}}"></script>
@endsection
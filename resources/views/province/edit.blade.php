@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت استان ها','url'=>url('admin/province')],
   ['title'=>'ویرایش استان','url'=>url('admin/province/'.$province->id.'/edit')]
   ]])
    <div class="panel">
        <div class="header">ویرایش استان - ( {{$province->name}} ) </div>
        <div class="panel_content">
            {!! Form::Model($province,['url'=>'admin/province/'.$province->id , 'files' => true]) !!}
            @method('PATCH')
            <div class="form-group">
                {!! Form::label('name','نام استان') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <button class="btn btn-primary">ویرایش اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
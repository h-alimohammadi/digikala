@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت محصولات','url'=>url('admin/product')],
   ['title'=>'افزودن محصول جدید','url'=>url('admin/product/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن محصول جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/product','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('title','عنوان محصول') !!}
                {!! Form::text('title',old('title'),['class'=>'form-control w-50']) !!}
                @if($errors->has('title'))
                    <span class="has_error">{{$errors->first('title')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::textarea('tozihat',old('tozihat'),['class'=>'form-control ckeditor']) !!}
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('ename','نام لاتین محصول') !!}
                        {!! Form::text('ename',old('ename'),['class'=>'form-control left']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('cat_id','انتخاب دسته :') !!}
                        {!! Form::select('cat_id',$parent_category,null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
                        @if($errors->has('cat_id'))
                            <span class="has_error">{{$errors->first('cat_id')}}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('brand_id','انتخاب برند :') !!}
                        {!! Form::select('brand_id',$brand,null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
                        @if($errors->has('brand_id'))
                            <span class="has_error">{{$errors->first('brand_id')}}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('color_id','انتخاب رنگ :') !!}
                        <select name="product_color[]" class="selectpicker" data-live-search='true' multiple="multiple">
                            @foreach($colors as $color)
                                <option value="{{$color->id}}"
                                        data-content="<span class='color_option' style='background:{{$color->code}}; @if($color->name == 'سفید') color:#000;  @endif'>{{$color->name}}</span>"></option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        {!! Form::label('status','انتخاب دسته :') !!}
                        {!! Form::select('status',$status,1,['class'=>'selectpicker','data-live-search'=>'true']) !!}
                        @if($errors->has('status'))
                            <span class="has_error">{{$errors->first('status')}}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="file" name="pic" id="pic" class="d-none" onchange="loadFile(event)">
                    <div class="choice_pic_box" onclick="select_file()">
                        <span class="title">انتخاب تصویر محصول</span>
                        <img id="output" class="pic_tag">
                    </div>
                    @if($errors->has('pic'))
                        <span class="has_error">{{$errors->first('pic')}}</span>
                    @endif

                </div>

            </div>

            <p class="message_txt">برچسب ها با استفاده از (،) از هم جدا شوند.</p>
            <div class="btn-group w-100">
                <input type="text" name="tag_list" id="tag_list" class="form-control w-100"
                       placeholder="برچسب های محصول...">
                <div class="btn btn-success" onclick="add_tags()">افزودن</div>
                <input name="keywords" id="keywords" type="hidden">
            </div>
            <div class="tag_box">

            </div>

            <div class="form-group">
                {!! Form::label('description','توضیحات مختصر  (حداکثر 150 کاراکتر)',['class'=>'text-danger w-100']) !!}
                {!! Form::textarea('description',old('description'),['class'=>'form-control w-100']) !!}
                @if($errors->has('description'))
                    <span class="has_error">{{$errors->first('description')}}</span>
                @endif
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="use_for_gift_cart" id="use_for_gift_cart">
                    استفاد به عنوان کارت هدیه
                </label>
            </div>
            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('ckeditor/ckeditor.js')}}" type="text/javascript"></script>
@endsection
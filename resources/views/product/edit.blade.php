@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت محصولات','url'=>url('admin/product')],
   ['title'=>'ویرایش محصول','url'=>url('admin/product/'.$product->id.'/edit')]
   ]])
    <div class="panel">
        <div class="header">ویرایش محصول - ( {{$product->title}} )</div>
        <div class="panel_content">
            {!! Form::Model($product,['url'=>'admin/product/'.$product->id , 'files' => true]) !!}
            @method('PATCH')
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
                                    <option value="{{$color->id}}" @if(array_key_exists($color->id,$productColor)) selected="selected"
                                            @endif
                                            data-content="<span class='color_option' style='background:{{$color->code}}; @if($color->name == 'سفید') color:#000;  @endif'>{{$color->name}}</span>"></option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        {!! Form::label('status','انتخاب دسته :') !!}
                        {!! Form::select('status',$status,null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
                        @if($errors->has('status'))
                            <span class="has_error">{{$errors->first('status')}}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="file" name="pic" id="pic" class="d-none" onchange="loadFile(event)">
                    <div class="choice_pic_box" onclick="select_file()">
                        <span class="title">انتخاب تصویر محصول</span>
                        <img id="output" class="pic_tag" src="{{asset('files/uploads/products/'.$product->image_url)}}">
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
                <input name="keywords" id="keywords" type="hidden" value="{{$product->keywords}}">
            </div>
            <div class="tag_box">
                @if(is_array(array_filter(explode('،',$product->keywords))))
                    @foreach(array_filter(explode('،',$product->keywords)) as $key=>$keyword)
                        <div class="tag_div" id="tag_div_{{$key}}">
                            <span class="fa fa-remove" onclick="remove_tag({{$key}},'{{$keyword}}')"></span>{{$keyword}}
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="form-group">
                {!! Form::label('description','توضیحات مختصر  (حداکثر 150 کاراکتر)',['class'=>'text-danger w-100']) !!}
                {!! Form::textarea('description',old('description'),['class'=>'form-control w-100']) !!}
                @if($errors->has('description'))
                    <span class="has_error">{{$errors->first('description')}}</span>
                @endif
            </div>
            <button class="btn btn-primary">ویرایش محصول</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('ckeditor/ckeditor.js')}}" type="text/javascript"></script>
@endsection
@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت تنوع های قیمت','url'=>url('admin/product_warranties?product_id='.$product->id)],
   ['title'=>'افزودن تنوع قیمت','url'=>url('admin/product_warranties/create?product_id='.$product->id)]
   ]])
    <div class="panel">
        <div class="header">افزودن تنوع قیمت جدید برای ( {{ $product->title }} )</div>
        <div class="panel_content">
            @include('Include.warning')
            {!! Form::open(['url'=>'admin/product_warranties?product_id='.$product->id]) !!}
            <div class="form-group">
                {!! Form::label('warranty_id','انتخاب گارانتی :') !!}
                {!! Form::select('warranty_id',$warranty,null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
                @if($errors->has('warranty_id'))
                    <span class="has_error">{{$errors->first('warranty_id')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('color_id','انتخاب رنگ :') !!}
                <select name="color_id" class="selectpicker" data-live-search='true'>
                    <option value="0">انتخاب رنگ</option>
                    @foreach($colors as $color)
                        <option value="{{$color->color->id}}"
                                data-content="<span class='color_option' style='background:{{$color->color->code}}; @if($color->color->name == 'سفید') color:#000;  @endif'>{{$color->color->name}}</span>"></option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                {!! Form::label('price1','قیمت محصول') !!}
                {!! Form::text('price1',old('name'),['class'=>'form-control left price_input']) !!}
                @if($errors->has('price1'))
                    <span class="has_error">{{$errors->first('price1')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('price2','قیمت محصول برای فروش') !!}
                {!! Form::text('price2',old('name'),['class'=>'form-control left discount_price_input']) !!}
                @if($errors->has('price2'))
                    <span class="has_error">{{$errors->first('price2')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('product_number','تعداد موجودی محصول') !!}
                {!! Form::text('product_number',old('name'),['class'=>'form-control left product_number']) !!}
                @if($errors->has('product_number'))
                    <span class="has_error">{{$errors->first('product_number')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('product_number_cart','تعداد سفارش در سبد خرید') !!}
                {!! Form::text('product_number_cart',old('name'),['class'=>'form-control left product_number_cart']) !!}
                @if($errors->has('product_number_cart'))
                    <span class="has_error">{{$errors->first('product_number_cart')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('send_time','زمان آماده سازی محصول') !!}
                {!! Form::text('send_time',old('name'),['class'=>'form-control left send_time']) !!}
                @if($errors->has('send_time'))
                    <span class="has_error">{{$errors->first('send_time')}}</span>
                @endif
            </div>
            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('script')
    <script  src="{{asset('js/cleave.min.js')}}" type="text/javascript"></script>
    <script>
        var cleave = new Cleave('.price_input', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave1 = new Cleave('.discount_price_input', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave2 = new Cleave('.product_number', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave3 = new Cleave('.product_number_cart', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave4 = new Cleave('.send_time', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        // var cleave1 = new Cleave('.input-element1', {
        //     numeral: true,
        //     numeralThousandsGroupStyle: 'thousand'
        // });
    </script>
@endsection
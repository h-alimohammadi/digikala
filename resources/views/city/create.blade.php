@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت شهر ها','url'=>url('admin/city')],
   ['title'=>'افزودن شهر','url'=>url('admin/city/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن دسته جدید</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/city','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('name','نام شهر :') !!}
                {!! Form::text('name',old('name'),['class'=>'form-control']) !!}
                @if($errors->has('name'))
                    <span class="has_error">{{$errors->first('name')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('province_id','انتخاب استان :') !!}
                {!! Form::select('province_id',$provinces,null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('send_time','زمان حدودی ارسال سفارش :') !!}
                {!! Form::text('send_time',old('send_time'),['class'=>'form-control send_time' ]) !!}
                @if($errors->has('send_time'))
                    <span class="has_error">{{$errors->first('send_time')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('send_price','هزینه ارسال سفارش :') !!}
                {!! Form::text('send_price',old('search_url'),['class'=>'form-control send_price']) !!}
                @if($errors->has('send_price'))
                    <span class="has_error">{{$errors->first('send_price')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('min_order_price','حداقل خرید برای ارسال رایگان :') !!}
                {!! Form::text('min_order_price',old('min_order_price'),['class'=>'form-control min_order_price']) !!}
                @if($errors->has('min_order_price'))
                    <span class="has_error">{{$errors->first('min_order_price')}}</span>
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
        var cleave = new Cleave('.send_time', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave1 = new Cleave('.send_price', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave2 = new Cleave('.min_order_price', {
            delimiter:',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    </script>
@endsection
@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'تایین هزینه ارسال سفارشات','url'=>url('admin/setting/send-order-price')],
   ]])
    <div class="panel">
        <div class="header">تایین هزینه ارسال سفارشات</div>
        <div class="panel_content">
            {!! Form::open(['url'=>'admin/setting/send-order-price','files'=>true]) !!}
            <div class="form-group">
                {!! Form::label('send_time','زمان حدودی ارسال سفارش :') !!}
                {!! Form::text('send_time',$data['send_time'],['class'=>'form-control left send_time']) !!}
                @if($errors->has('send_time'))
                    <span class="has_error">{{$errors->first('send_time')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('send_price','هزینه ارسال سفارش') !!}
                {!! Form::text('send_price',$data['send_price'],['class'=>'form-control left send_price']) !!}
                @if($errors->has('send_price'))
                    <span class="has_error">{{$errors->first('send_price')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('min_order_price','حداقل خرید برای ارسال رایگان :') !!}
                {!! Form::text('min_order_price',$data['min_order_price'],['class'=>'form-control left min_order_price']) !!}
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

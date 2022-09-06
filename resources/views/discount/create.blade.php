@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت کدهای تخفیف','url'=>url('admin/discount')],
   ['title'=>'افزودن کد تخفیف','url'=>url('admin/discount/create')]
   ]])
    <div class="panel">
        <div class="header">افزودن کد تخفیف</div>
        <div class="panel_content discount_form">
            {!! Form::open(['url'=>'admin/discount']) !!}
            <div class="form-group">
                {!! Form::label('code','کد تخفیف :') !!}
                {!! Form::text('code',old('code'),['class'=>'form-control left']) !!}
                @if($errors->has('code'))
                    <span class="has_error">{{$errors->first('code')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('expire_time','تاریخ انقضاء :') !!}
                {!! Form::text('expire_time',old('code'),['class'=>'form-control pdate text-center','id'=>'pcal1']) !!}
                @if($errors->has('expire_time'))
                    <span class="has_error">{{$errors->first('expire_time')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('cat_id','انتخاب دسته :') !!}
                {!! Form::select('cat_id',$cat,null,['class'=>'selectpicker','data-live-search'=>'true']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('amount','حداقل خرید :') !!}
                {!! Form::text('amount',old('amount'),['class'=>'form-control left','id'=>'amount']) !!}
                @if($errors->has('amount'))
                    <span class="has_error">{{$errors->first('amount')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('number_usable','حداکثر دفعات استفاده :') !!}
                {!! Form::text('number_usable',old('amount'),['class'=>'form-control left','id'=>'number_usable']) !!}
                @if($errors->has('number_usable'))
                    <span class="has_error">{{$errors->first('number_usable')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('amount_discount','میزان کد تخفیف( بر حسب تومان ) :') !!}
                {!! Form::text('amount_discount',old('amount_discount'),['class'=>'form-control left','id'=>'amount_discount']) !!}
                @if($errors->has('amount_discount'))
                    <span class="has_error">{{$errors->first('amount_discount')}}</span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('amount_percent','میزان کد تخفیف( بر حسب درصد ) :') !!}
                {!! Form::text('amount_percent',old('amount_percent'),['class'=>'form-control left','id'=>'amount_percent']) !!}
                @if($errors->has('amount_percent'))
                    <span class="has_error">{{$errors->first('amount_percent')}}</span>
                @endif
            </div>
            <div class="form-group">
                <label>استفاده برای پیشنهادات شگفت انگیز :</label>
                <input type="checkbox" name="incredible_offers">
            </div>

            <button class="btn btn-success">ثبت اطلاعات</button>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('link')
    <link rel="stylesheet" href="{{asset('css/js-persian-cal.css')}}"/>
@endsection
@section('script')
    <script src="{{asset('js/js-persian-cal.min.js')}}"></script>
    <script src="{{asset('js/cleave.min.js')}}" type="text/javascript"></script>
    <script>
        var pcal1 = new AMIB.persianCalendar('pcal1');
    </script>
    <script>
        var cleave = new Cleave('#amount', {
            delimiter: ',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave1 = new Cleave('#number_usable', {
            delimiter: ',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave2 = new Cleave('#amount_discount', {
            delimiter: ',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var cleave3 = new Cleave('#amount_percent', {
            delimiter: ',',
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    </script>
@endsection

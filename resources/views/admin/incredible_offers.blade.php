@extends('layouts.admin')
@section('link')
    <link rel="stylesheet" href="{{asset('css/js-persian-cal.css')}}"/>
@endsection
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت محصولات پیشنهاد شگفت انگیز','url'=>url('admin/incredible-offers')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت محصولات پیشنهاد شگفت انگیز
        </div>
        <div class="panel_content">
            @include('Include.alert')
            <incredible-offers></incredible-offers>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset('js/js-persian-cal.min.js')}}"></script>
    <script>
        var pcal1 = new AMIB.persianCalendar('pcal1');
        var pcal2 = new AMIB.persianCalendar('pcal2');
    </script>
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
@endsection

@extends('layouts.mobile_order')

@section('content')
    <div class="order_header">
        <img src="{{asset('files/images/shop_icon.jpg')}}" class="shop_icon">
        <ul class="checkout_steps">
            <li>
                <a class="checkout_step">
                    <div class="step_item active_item" step-title="اطلاعات ارسال"></div>
                </a>
            </li>
            <li class="inactive">
                <a class="checkout_step">
                    <div class="step_item" step-title="پرداخت"></div>
                </a>
            </li>
            <li class="inactive">
                <a class="checkout_step">
                    <div class="step_item" step-title="اتمام خرید"></div>
                </a>
            </li>
        </ul>
    </div>
    <div class="page_row">
        <div class="page_content">
            <form action="{{url('payment')}}" id="add_order" method="post">
                @csrf
                <input type="hidden" id="address_id" name="address_id">
                <input type="hidden" id="lng" name="lng" value="0.0">
                <input type="hidden" id="lat" name="lat" value="0.0">
                <input type="hidden" id="sent_type" name="sent_type" value="1">
            </form>
            <mobile-address-list :data="{{json_encode($address)}}"></mobile-address-list>
        </div>
    </div>
@endsection
@section('script')
    {{--    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY&callback=myMap"></script>--}}
    {{--    <script>--}}
    {{--    function myMap() {--}}
    {{--        var mapCanvas = document.getElementById("map");--}}
    {{--        var mapOptions = {--}}
    {{--            center: new google.maps.LatLng(51.5, -0.2),--}}
    {{--            zoom: 10--}}
    {{--        };--}}
    {{--        var map = new google.maps.Map(mapCanvas, mapOptions);--}}
    {{--    }--}}
    {{--</script>--}}
@endsection
@extends('layouts.order')
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
                    <div class="step_item" step-title="اتمام خرید و ارسال"></div>
                </a>
            </li>
        </ul>
    </div>
    <div class="container-fluid">
        <div class="row headline_checkout">
            <h6>انتخاب آدرس تحویل سفارش</h6>
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
                <address-list :data="{{json_encode($address)}}"></address-list>
            </div>
            <div class="page_aside">
                <div class="order_info">
                    @php
                        $total_product_price=Session::get('total_product_price',0);
                        $final_price=Session::get('final_price',0);
                        $discount =$total_product_price-$final_price;
                    @endphp
                    <ul>
                        <li>
                            <span>مبلغ کل</span>
                            <span>({{replace_number(replace_number(number_format(\App\Cart::getProductCount())))}}) کالا </span>
                            <span class="left">{{ replace_number(number_format($total_product_price)) }} تومان </span>
                        </li>
                        @if($discount !=0)
                            <li class="cart_discount_li">
                                <span>سود شما از خرید   </span>
                                <span class="left">{{ replace_number(number_format($discount)) }} تومان </span>
                            </li>
                        @endif
                        <li>
                            <span>هزینه ارسال</span>
                            <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom"
                                  title="هزینه ارسال مرسولات میتواند وابسته به شهر و آدرس گیرنده متفاوت باشد. در صورتی که هر یک از مرسولات حداقل ارزشی برابر با 150 هزار تومان داشته باشد. آن مرسوله بصورت رایگان ارسال میشود."></span>
                            <span class="left" id="total_send_order_price"></span>
                        </li>
                    </ul>
                    <div class="checkout_devider"></div>
                    <div class="checkout_content">
                        <p style="color: red">مبلغ قابل پرداخت</p>
                        <p id="final_price">{{replace_number(number_format($final_price))}} تومان</p>
                    </div>
                    <a onclick="$('#add_order').submit();">
                        <div class="send_btn checkout">
                            <span class="line"></span>
                            <span class="title">ادامه ثبت سفارش</span>
                        </div>
                    </a>
                </div>
            </div>
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
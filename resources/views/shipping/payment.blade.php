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
            <li class="active">
                <a class="checkout_step">
                    <div class="step_item active_item" step-title="پرداخت"></div>
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
            <h6>انتخاب شیوه پرداخت</h6>
        </div>
        <div class="page_row">
            @php
                $cart_final_price=$send_type == 1? $send_order_data['normal_cart_price'] : $send_order_data['fasted_cart_amount'] ;
                $total_product_price=Session::get('total_product_price',0);
                $final_price=Session::get('final_price',0);
            @endphp
            <div class="page_content">
                <div class="shipping_data_box payment_box mt-0">
                    <span class="radio_check active_radio_check"></span>
                    <span class="label">پرداخت اینترنتی (آنلاین با تمامی کارت های بانکی)</span>
                </div>
                <h6>خلاصه سفارش</h6>
                <div class="shipping_data_box" style="padding-right: 15px;padding-left: 15px">
                    @php($i =0)
                    @if($send_type == 1)
                        <div class="shipping_data_box p-0">
                            <div class="header_box">
                                <div>
                                    مرسوله ۱ از ۱
                                    <span>({{ \App\Cart::getProductCount() }} کالا)</span>
                                </div>
                                <div>
                                    نحوه ارسال
                                    <span>پست پیشتاز با ظرفیت اختصاصی برای دیجی آنلاین</span>
                                </div>
                                <div>
                                    ارسال از
                                    <span>
                                        @if($send_order_data['normal_send_day']==0)
                                            آماده ارسال
                                        @else
                                            {{replace_number($send_order_data['normal_send_day'])}}روز کاری
                                        @endif
                                        </span>
                                </div>
                                <div>
                                    هزینه ارسال
                                    <span>{{$send_order_data['normal_send_order_amount']}}</span>
                                </div>
                            </div>
                            <div class="ordering_product_list swiper-container">
                                <div class="swiper-wrapper swiper_product_box">
                                    @foreach($send_order_data['cart_product_data'] as $product)
                                        <div class="product_info_box swiper-slide">
                                            <img src="{{asset('files/uploads/thumbnails/'.$product['product_image_url'])}}">
                                            <p class="product_title text-center">{{$product['product_title']}}</p>
                                            @if(isset($product['color_name']))
                                                <p class="product_color text-center">رنگ
                                                    : {{ $product['color_name'] }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-next" id="next"></div>
                                <div class="swiper-button-prev" id="prev"></div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    @else
                        @foreach($send_order_data['delivery_order_interval'] as $key=>$value)
                            <div class="shipping_data_box p-0">
                                <div class="header_box">
                                    <div>
                                        مرسوله {{replace_number(++$key)}}
                                        از {{replace_number(sizeof($send_order_data['delivery_order_interval']))}}
                                        <span>({{ replace_number(sizeof($send_order_data['array_product_id'][$i])) }} کالا)</span>
                                    </div>
                                    <div>
                                        نحوه ارسال
                                        <span>پست پیشتاز با ظرفیت اختصاصی برای دیجی آنلاین</span>
                                    </div>
                                    <div>
                                        ارسال از
                                        <span>
                                            @if($value['send_order_day_number']==0)
                                                آماده ارسال
                                            @else
                                                {{replace_number($value['send_order_day_number'])}}روز کاری
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        هزینه ارسال
                                        <span>{{$value['send_fast_price']}}</span>
                                    </div>
                                </div>
                                <div class="ordering_product_list swiper-container">
                                    <div class="swiper-wrapper swiper_product_box">
                                        @foreach($send_order_data['array_product_id'][$i] as $key2=>$value2)
                                            @php($product=$send_order_data['cart_product_data'][$value2.'_'.$key2])
                                            <div class="product_info_box swiper-slide">
                                                <img src="{{asset('files/uploads/thumbnails/'.$product['product_image_url'])}}">
                                                <p class="product_title">{{$product['product_title']}}</p>
                                                @if(isset($product['color_name']))
                                                    <p class="product_color">رنگ : {{ $product['color_name'] }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-button-next" id="next"></div>
                                    <div class="swiper-button-prev" id="prev"></div>
                                    <div class="swiper-pagination"></div>

                                </div>
                            </div>
                            @php($i++)
                        @endforeach
                    @endif
                </div>

                <discount-box></discount-box>
                <gift-cart></gift-cart>

            </div>

            <div class="page_aside">
                <div class="order_info">

                    <ul>
                        <li>
                            <span>مبلغ کل</span>
                            <span>({{replace_number(replace_number(number_format(\App\Cart::getProductCount())))}}) کالا </span>
                            <span class="left">{{ replace_number(number_format($final_price))}}</span>
                        </li>
                        <li>
                            <span>هزینه ارسال</span>
                            <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom"
                                  title="هزینه ارسال مرسولات میتواند وابسته به شهر و آدرس گیرنده متفاوت باشد. در صورتی که هر یک از مرسولات حداقل ارزشی برابر با 150 هزار تومان داشته باشد. آن مرسوله بصورت رایگان ارسال میشود."></span>
                            <span class="left" id="total_send_order_price">
                                {{ $send_type == 1 ? $send_order_data['normal_send_order_amount'] : $send_order_data['total_fast_send_amount'] }}
                            </span>
                        </li>
                        <li class="gift_li" @if(Session::has('gift_value')) style="display: block;" @endif>
                            <span>کارت هدیه</span>
                            <span class="left" id="gift_cart_amount">
                                {{replace_number(number_format(Session::get('gift_value',0))).' تومان'}}
                            </span>
                        </li>
                        <li class="discount_li" @if(Session::has('discount_value')) style="display: block;" @endif>
                            <span>کد تخفیف</span>
                            <span class="left" id="discount_cart_amount">
                                {{replace_number(number_format(Session::get('discount_value',0))).' تومان'}}
                            </span>
                        </li>
                    </ul>
                    <div class="checkout_devider"></div>
                    <div class="checkout_content">
                        <p style="color: red">مبلغ قابل پرداخت</p>
                        <p id="final_price">{{$cart_final_price}} </p>
                    </div>
                    <a href="{{url('order/payment')}}">
                        <div class="send_btn checkout">
                            <span class="line"></span>
                            <span class="title">پرداخت و ثبت نهایی سفارش</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('link')
    <link href="{{ asset('css/swiper-bundle.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{asset('js/swiper.min.js')}}" type="text/javascript"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 4,
            spaceBetween: 0,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            }
        });

    </script>

@endsection
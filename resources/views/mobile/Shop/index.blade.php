@extends('layouts.mobile')
@section('link')
    <link href="{{asset('css/swiper-bundle.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="slider_box">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($sliders as $key=>$value)
                    <div class="swiper-slide">
                        <a href="{{$value->url}}">
                            <img src="{{asset('files/uploads/slider/mobile/'.$value->mobile_image_url)}}">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    @if(sizeof($productWarranty)>0)
        <img class="incredible-offers-img" src="{{ asset('files/images/7a0e5239.png') }}">
        <div class="index_product_box">
            <div class="product_box">
                <div class="swiper-container products">
                    <div class="swiper-wrapper">
                        @foreach($productWarranty as $key=>$value)
                            @php
                                $price1 = $value->product->price+$value->product->discount_price;
                            @endphp
                            <div class="swiper-slide product">
                                <a href="{{url('product/dkp-'.$value->product->id.'/'.$value->product->product_url)}}">
                                    <div class="position-relative">
                                     <span class="discount-badge">
                                        @php
                                            $d= ($value->price2/$value->price1)*100;
                                            $d =100-$d;
                                            $d = round($d);
                                        @endphp
                                        ٪{{replace_number($d)}}
                                    </span>
                                        <img src="{{url('files/uploads/thumbnails/'.$value->product->image_url)}}">
                                    </div>
                                    <p class="title">
                                        @if(strlen($value->product->title)>50)
                                            {{ mb_substr($value->product->title,0,33).'...' }}
                                        @else
                                            {{$value->product->title }}
                                        @endif
                                    </p>
                                    @if($value->product_number>0)
                                        <del class="price_tag">
                                            <div>{{ replace_number(number_format($value->price1)) }} تومان</div>
                                        </del>
                                        <span class="price price_tag ">
                                            <div>{{ replace_number(number_format($value->price2)) }} تومان</div>
                                        </span>
                                        <div class="offers_counter">
                                            <counter second="{{$value->offers_last_time-time() }}"></counter>
                                        </div>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="index_product_box">
        @include('Include.horizontal_product_list2',['productList'=>$newProduct,'title'=>'جدید ترین محصولات فروشگاه'])
    </div>
    <div class="banners_div">
        <img class="banners" src="{{ url('files/images/1000007982.jpg') }}">
    </div>
    <div class="banners_div">
        <img class="banners" src="{{ url('files/images/1000008130.jpg') }}">
    </div>
    <div class="index_product_box">
        @include('Include.horizontal_product_list2',['productList'=>$newProduct,'title'=>'جدید ترین محصولات فروشگاه'])
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/swiper.min.js') }}"></script>
    <script>
        var sliders = new Swiper('.slider_box .swiper-container', {
            pagination: {
                el: '.swiper-pagination'
            }
        });
    </script>
    <script>
        var productSlider = new Swiper('.product_box .swiper-container', {
            slidesPerView: 2,
            spaceBetween: 10
        });
    </script>
@endsection
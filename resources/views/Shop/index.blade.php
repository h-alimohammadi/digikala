@extends('layouts.Shop')

@section('content')
    <div class="row slider">
        <div class="col-2 p-0">
            <div>
                <a href="{{url('')}}">
                    <img src="{{url('files/images/1000007524.jpg')}}"
                         @if(sizeof($productWarranty)==0) style="height: 154px;" @endif class="index_pic">
                </a>
                <a href="{{url('')}}">
                    <img src="{{url('files/images/1000007568.jpg')}}"
                         @if(sizeof($productWarranty)==0) style="height: 154px;" @endif class="index_pic">
                </a>
                @if(sizeof($productWarranty)>0)
                    <a href="{{url('')}}">
                        <img src="{{url('files/images/1000005397.jpg')}}" class="index_pic">
                    </a>
                    <a href="{{url('')}}">
                        <img src="{{url('files/images/1000005397.jpg')}}" class="index_pic">
                    </a>
                @endif
            </div>
        </div>
        <div class="col-10">
            @if(sizeof($sliders)>0)
                <div class="slider_box">
                    <div class="position-relative">
                        @foreach($sliders as $key=>$slider)
                            <div class="slide_div an" id="slider_img_{{$key}}"
                                 @if($key==0) style="display: block;" @endif >
                                <a href="{{$slider->url}}"
                                   style='background-image: url("{{asset('files/uploads/slider/'.$slider->image_url)}}")'></a>
                            </div>
                        @endforeach
                    </div>
                    <div class="right_slide" onclick="previous()"></div>
                    <div class="left_slide" onclick="next()"></div>
                    <div class="slider_box_footer position-absolute">
                        <div class="slider_bullet_div">
                            @foreach($sliders as $key=>$slider)
                                <span @if($key==0) class="active" @endif id="slide_bullet_{{$key}}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @include('Include.incredible-offers',['productWarranty'=>$productWarranty])


        </div>

    </div>
    <div class="row">
        @include('Include.horizontal_product_list',['productList'=>$newProduct,'title'=>'جدید ترین محصولات فروشگاه'])
        @include('Include.horizontal_product_list',['productList'=>$bestSellingProduct,'title'=>'پرفروش ترین محصولات فروشگاه'])
    </div>

@endsection
@section('link')
    <link href="{{ asset('css/swiper-bundle.css') }}" rel="stylesheet">
    <link href="{{ asset('slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('slick/slick-theme.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{asset('slick/slick.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/swiper.min.js')}}" type="text/javascript"></script>
    <script>
        load_slider('{{sizeof($sliders)}}');
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            spaceBetween: 10,
            navigation: {
                nextEl: '.slick-next',
                prevEl: '.slick-prev',
            }
        });
        @php
            if (sizeof($productWarranty) <6){
        @endphp
        $("#next").hide();
        $("#prev").hide();
        @php
            }
        @endphp
        $(".product_list").slick({
            speed: 900,
            slidesToShow: 4,
            slidesToScroll: 4,
            rtl: true,
            infinite: false,
        });
    </script>

@endsection

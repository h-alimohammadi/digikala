@extends('layouts.mobile')

@section('content')
    <div class="position-relative pb-5">
        <div class="product_item_box margin">
            <div class="product_headline">
                <h6 class="product_title">
                    {{ $product->title }}
                    @if(!empty($product->ename) && $product->ename!= null)
                        <span>{{ $product->ename }}</span>  @endif
                </h6>
            </div>
            <div class="product_options">
                <div>
                    <span class="fa fa-heart-o"></span>
                    <span class="fa fa-share-alt"></span>
                    <span class="fa fa-line-chart"></span>
                </div>
                <div class="d-flex align-items-center">
                    @php
                        $width=0;
                        if ($product->score_count>0){
                            $width=$product->score/($product->score_count*6);
                        }
                    $width*=20;
                    @endphp
                    <span>{{ replace_number($product->score_count) }} نفر</span>
                    <div class="score">
                        <div class="gray">
                            <div class="red" style="width: {{ $width }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @if(sizeof($product->ProductGallery)>0)
                    <div class="swiper-container" id="gallery" dir="rtl">
                        <div class="swiper-wrapper">
                            @foreach($product->ProductGallery as $key=>$value)
                                <div class="swiper-slide">
                                    <img src="{{ asset('files/uploads/products/gallerys/'.$value->image_url) }}">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                @else
                    <img src="{{ asset('files/uploads/products/'.$product->image_url) }}" class="product_image">
                @endif
            </div>
            <div class="row">
                <ul class="list-inline product_data_ul">
                    <li>
                        <span>برند :</span>
                        <a href="{{url('brand/'.$product->brand->ename)}}" class="data_link">

                            <span>{{ $product->brand->name }}</span>
                        </a>

                    </li>
                    <li>
                        <span>دسته بندی :</span>
                        <a href="{{url('')}}" class="data_link">
                                <span>
                                    {{ $product->category->name }}
                                </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="product_item_box">
            <div style="padding: 20px">
                @if($product->status==1)
                    <div id="warranty_box">
                        @include('Include.warranty',['color_id'=>0])
                    </div>
                @else
                    <div class="product_unavailable">
                        <span>ناموجود</span>
                        <p>
                            متاسفانه این کالا در حال حاضر موجود نیست. میتوانیداز طریق لیست محصولات مرتبط، از محصولات
                            مشابه این کالا دیدن فرمایید.
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @if($product->status==1)
            <mobile-other-price :product_id="{{ $product->id }}"></mobile-other-price>
        @endif
        @if($product->status==1)
            <div class="add_product_link">
                <span>افزودن محصول به سبد خرید</span>
            </div>
        @endif
        @if($productItemCount > 0)
            <div class="product_item_box">
                <div style="padding: 15px">
                    <div class="item_box">
                        <span>مشخصات فنی</span>
                        <a id="show_item_product">
                            <span>موارد بیشتر</span>
                            <span class="fa fa-angle-left"></span>
                        </a>
                    </div>
                    @include('Include.show_important_item',['remove_title'=>true])
                </div>
            </div>
        @endif

    </div>

     @include('mobile.product_item_list')
@endsection
@section('link')
    <link href="{{asset('css/swiper-bundle.css')}}" rel="stylesheet">
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('js/swiper.min.js') }}"></script>
    <script>
        var sliders = new Swiper('#gallery', {
            pagination: {
                el: '.swiper-pagination',
                dynamicBullets: true,
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
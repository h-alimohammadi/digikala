@extends('layouts.Shop')

@section('content')

    <div class="content">
        <div class="product_info">
            <div class="product_image_box">
                <offer-time></offer-time>
                <div>
                    <ul class="product_options">
                        <li data-toggle="tooltip" data-placement="left" title="افزودن به علاقه مندی ها">
                            <a>
                                <span class="fa fa-heart-o"></span>
                            </a>
                        </li>
                        <li data-toggle="tooltip" data-placement="left" title="اشتراک گذاری">
                            <a>
                                <span class="fa fa-share-alt"></span>
                            </a>
                        </li>
                        <li data-toggle="tooltip" data-placement="left" title="مقایسه">
                            <a>
                                <span class="fa fa-compress"></span>
                            </a>
                        </li>
                        <li data-toggle="tooltip" data-placement="left" title="نمودار قیمت">
                            <a>
                                <span class="fa fa-line-chart"></span>
                            </a>
                        </li>
                    </ul>
                    @if(!empty($product->image_url))
                       <div class="default_product_pic">
                           <img src="{{ url('files/uploads/products/'.$product->image_url) }}">
                       </div>
                    @endif
                </div>
            </div>
            <div class="product_data">
                <div class="product_headline">
                    <h6 class="product_title">
                        {{ $product->title }}
                        @if(!empty($product->ename) && $product->ename!= null)
                            <span>{{ $product->ename }}</span>  @endif
                    </h6>
                </div>
                <div>
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
                    <div class="row">
                        <div class="col-8">
                            <div id="warranty_box">
                                @include('Include.warranty',['color_id'=>0])
                            </div>
                            <div class="send_btn">
                                <span class="line"></span>
                                <span class="title">افزودن به سبد خرید</span>
                            </div>
                        </div>
                        <div class="col-4">
                            @include('Include.show_important_item')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('Include.horizontal_product_list',['productList'=>$relateProducts,'title'=>'محصولات مرتبط'])

        <div id="tab_div">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link " data-toggle="tab" href="#review" role="tab"aria-selected="true">
                        <span class="fa fa-camera-retro"></span>
                        <span>نقد و بررسی</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#product_id" role="tab" aria-selected="false">
                        <span class="fa fa-list-ul"></span>
                        <span>مشخصات فنی</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#comments" role="tab" aria-selected="false">
                        <span class="fa fa-comment-o"></span>
                        <span>نظرات کاربران</span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#questions" role="tab" aria-selected="false">
                        <span class="fa fa-question-circle"></span>
                        <span>پرسش و پاسخ</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="home-tab">...</div>
                <div class="tab-pane fade show active" id="product_id" role="tabpanel" aria-labelledby="profile-tab">
                    @include('Include.product_items')
                </div>
                <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="contact-tab">...</div>
                <div class="tab-pane fade" id="questions" role="tabpanel" aria-labelledby="contact-tab">...</div>
            </div>

        </div>
    </div>
@endsection
@section('link')
    <link href="{{ asset('slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('slick/slick-theme.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{asset('slick/slick.min.js')}}" type="text/javascript"></script>
    <script>
        $(".product_list").slick({
            speed: 900,
            slidesToShow: 4,
            slidesToScroll: 4,
            rtl: true,
            infinite: false,
        });
    </script>

@endsection
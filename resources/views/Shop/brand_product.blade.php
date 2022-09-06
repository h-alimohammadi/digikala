@extends('layouts.Shop')

@section('content')
    <div class="row" id="product_box">
        <div class="col-3">
            <div class="item_box">
                <div class="brand_info">
                    <img src="{{asset('files/uploads/brand/'.$brand->icon)}}">
                    <p>{{ $brand->name }}</p>
                    <p><a href="{{ url('brand/'.$brand->ename) }}">{{ $brand->ename }}</a></p>
                </div>
            </div>
            <div class="item_box">
                <div class="title_box">
                    <label>جست و جو در نتایج :</label>
                    <span class="fa fa-angle-down"></span>
                </div>
                <div>
                    <input type="text" class="form-control"
                           @if(array_key_exists('string',$_GET)) value="{{$_GET['string']}}"
                           @endif id="search_input" placeholder="نام محصول، برند را وارد کنید...">
                </div>
            </div>
            @if(sizeof($brand->getCat)>0)
                <div class="item_box">
                    <div class="title_box">
                        <label>دسته بندی</label>
                        <span class="fa fa-angle-down"></span>
                    </div>
                    <div>
                        <div class="filter_box filter_brand_div " style="display: block">
                            <input type="text" class="form-control" id="brand_search"
                                   placeholder="جست و جو نام برند ...">
                            <ul class="list-inline product_cat_ul brand_list">
                                @foreach($brand->getCat as $key=>$value)
                                    <li data="category_param_{{ $value->cat_id }}">
                                        <span class="check_box"></span>
                                        <span class="title">{{ $value->category->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="item_box toggle_box">
                <div class="toggle-light" id="product_status">
                </div>
                <span>فقط کالاهای موجود</span>

            </div>
            <div class="item_box toggle_box">
                <div class="toggle-light" id="send_status">
                </div>
                <span>فقط کالاهای آماده ارسال</span>
            </div>
            <div class="item_box">
                <div class="title_box">
                    <label>محدوده قیمت مورد نظر</label>
                    <span class="fa fa-angle-down"></span>
                </div>
                <div>
                    <div class="range_slider_div">
                        <div id="slider" class="price_range_slider"></div>
                    </div>
                    <ul class="filter_price_ul">
                        <li>
                            <div>از</div>
                            <div class="price" id="min_price"></div>
                            <div>تومان</div>
                        </li>
                        <li>
                            <div>تا</div>
                            <div class="price" id="max_price"></div>
                            <div>تومان</div>
                        </li>
                    </ul>
                    <button class="btn btn-primary" id="price_filter_btn">
                        <span class="fa fa-filter"></span>
                        <span>اعمال محدوده قیمت</span>
                    </button>
                </div>
            </div>

        </div>
        <div class="col-9">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="list-inline map_ul">
                    <li>
                        <a href="{{ url('/') }}">فروشگاه</a>
                       /
                    </li>
                    <li><a href="{{url('brand/'.$brand->ename)}}">{{$brand->name}}</a></li>

                </ul>
                <div id="product_count"></div>
            </div>
            <product-box :compare="'no'"></product-box>
        </div>
    </div>
@endsection

@section('link')
    <link rel="stylesheet" href="{{ url('css/toggles-full.css') }}">
    <link rel="stylesheet" href="{{ url('css/nouislider.min.css') }}">
    <script src="{{ url('js/nouislider.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/toggles.min.js') }}" type="text/javascript"></script>
@endsection


@section('script')
    <script src="{{ url('js/toggles.min.js') }}" type="text/javascript"></script>
    <script>
        $('#product_status').toggles({
            type: 'Light',
            text: {'on': '', 'off': ''},
            width: 50,
            direction: 'rtl',
            on: false,
        });
        $('#send_status').toggles({
            type: 'Light',
            text: {'on': '', 'off': ''},
            width: 50,
            direction: 'rtl',
            on: false,
        });
    </script>
@endsection


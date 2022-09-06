@extends('layouts.Shop')

@section('content')
    <div class="row" id="product_box">
        <div class="col-3">
            <div class="item_box" id="filter_div"
                 @if(sizeof($_GET)==0 || sizeof($_GET)==1 && array_key_exists('page',$_GET)) style="display: none" @endif>
                <div class="title_box">
                    <label>فیلتر های اعمال شده</label>
                    <span id="remove_all_filter">حذف</span>
                </div>
                <div id="select_filter_box">

                </div>
            </div>
            @if(isset($category) && sizeof($category->getChild)>0)
                <div class="item_box">
                    <div class="title_box">
                        <label>دسته بندی</label>
                        <span class="fa fa-angle-down"></span>
                    </div>
                    <ul class="search_category_ul">
                        <li class="parent">
                            <a href="{{ url('search/'.$category->url) }}">{{$category->name}}</a>
                            <ul>
                                @foreach($category->getChild as $cat)
                                    <li>
                                        <a href="{{ url('search/'.$cat->url) }}">{{$cat->name}}</a>
                                    </li>                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            @endif
            @if(isset($brands) && sizeof($brands)>0)
                <div class="item_box">
                    <div class="title_box">
                        <label>برند</label>
                        <span class="fa fa-angle-down"></span>
                    </div>
                    <div>
                        <div class="filter_box filter_brand_div " style="display: block">
                            <input type="text" class="form-control" id="brand_search"
                                   placeholder="جست و جو نام برند ...">
                            <ul class="list-inline product_cat_ul brand_list">
                                @foreach($brands as $key=>$value)
                                    <li data="brand_param_{{ $value->brand_id }}">
                                        <span class="check_box"></span>
                                        <span class="title">{{ $value->getBrand->name }}</span>
                                        <span class="ename">{{ $value->getBrand->ename }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
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
            @if(sizeof($colors)>1)
                <div class="item_box">
                    <div class="title_box">
                        <label>رنگ ها</label>
                        <span class="fa fa-angle-down"></span>
                    </div>
                    <div>
                        <div class="filter_box">
                            <ul class="list-inline product_cat_ul">
                                @foreach($colors as $key=>$value)
                                    <li data="color_param_{{ $value->id }}"
                                        class="d-flex justify-content-between align-items-center pl-2">
                                        <div>
                                            <span class="check_box"></span>
                                            <span class="title">{{ $value->name }}</span>
                                        </div>
                                        <div class="color_div"
                                             style="background: {{$value->code}};@if($value->name == 'سفید') border: 1px solid black; @endif"></div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($filters) && sizeof($filters)>1)
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
                @foreach($filters as $key=>$value)
                    <div class="item_box">
                        <div class="title_box">
                            <label>{{ $value->title }}</label>
                            <span class="fa fa-angle-down"></span>
                        </div>
                        <div>
                            <div class="filter_box">
                                <ul class="list-inline product_cat_ul">
                                    @foreach($value->getChild as $key2=>$value2)
                                        @php
                                            $filter_key='attribute['.$value->id.']';
                                        @endphp
                                        <li data="{{ $filter_key }}_param_{{ $value2->id }}">
                                            <span class="check_box"></span>
                                            <span class="title">{{ $value2->title }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
        <div class="col-9 position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="list-inline map_ul">
                    <li>
                        <a href="{{ url('/') }}">فروشگاه</a>
                        @if(isset($category)) / @endif
                    </li>
                    @if(isset($category))
                        @if($category->getParent->getParent->name != '-')
                            <li><a href="{{url('main/'.$category->getParent->getParent->url)}}">{{$category->getParent->getParent->name}}</a> / </li>
                        @endif
                        @if($category->getParent->name != '-')
                            <li><a href="{{url('search/'.$category->getParent->url)}}">{{$category->getParent->name}}</a> / </li>
                        @endif
                        <li>
                            <a href="{{url()->current()}}">{{ $category->name }}</a>
                        </li>
                    @endif
                </ul>
                <div id="product_count"></div>
            </div>
            <product-box :compare="'yes'"></product-box>
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


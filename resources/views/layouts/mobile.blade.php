<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>فروشگاه دیجی آنلاین</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('link')
    <link href="{{ asset('css/mobile.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/mobile.js') }}"></script>

</head>
<body>
<div id="app">
    @include('mobile.CatList')
    <div>
        <div class="header">
            <span class="fa fa-align-justify"></span>
            <a href="{{url('/')}}">
                <span>فروشگاه اینترنتی {{ env('SHOP_NAME','') }}</span>
            </a>
            <span></span>
        </div>
    </div>
    <div class="navbar">
        <div class="input-group index_search_form">
            <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="جست جو در بیش از 1000 کالا">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <span class="fa fa-search"></span>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ url('Cart') }}" class="position-relative">
                @if(\App\Cart::getProductCount()>0)
                    <span class="cart_product_count">{{ replace_number(\App\Cart::getProductCount()) }}</span>
                @endif
                <span class="fa fa-shopping-basket"></span>
                @if(Auth::check())
                    <a href="{{ url('profile') }}"><span class="fa fa-user-o"></span></a>
                @else
                        <a href="{{ url('login') }}"><span class="fa fa-user-o"></span></a>
                @endif
            </a>
        </div>
    </div>
    <div class="container-fluid p-0">
        @yield('content')

    </div>

    @include('mobile.footer')
</div>
<div id="loading_box">
    <div class="loading_div">
        <img src="{{asset('files/images/shop_icon.jpg')}}">
        <div class="spinner">
            <div class="b1"></div>
            <div class="b2"></div>
            <div class="b3"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/ShopVue.js') }}"></script>
@yield('script')
</body>
</html>
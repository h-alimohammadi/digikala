<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>فروشگاه دیجی آنلاین</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('link')
    <link href="{{ asset('css/shop.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/shop.js') }}"></script>

</head>
<body>
<div id="app">
    <div class="header">

        <a href="{{url('/')}}">
            <img src="{{asset('files/images/shop_icon.jpg')}}" class="shop_logo">
        </a>
        <div class="header_row">

            <div class="input-group index_header_search">
                <input type="text" class="form-control" id="inlineFormInputGroup"
                       placeholder="نام کالا، برند و یا دسته مورد نظر خود را جستجو کنید...">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <span class="fa fa-search"></span>
                    </div>
                </div>
            </div>

            <div class="header_action">
                <div class="dropdown">
                    <div class="index_auth_div" role="button" data-toggle="dropdown">
                        <span>
                            @if(Auth::check())
                                @if(!empty(auth()->user()->name))
                                    {{ auth()->user()->name }}
                                @else
                                    {{ replace_number(auth()->user()->mobile) }}
                                @endif

                            @else
                                ورود / ثبت نام
                            @endif
                        </span>
                        <span class="fa fa-angle-down"></span>
                    </div>
                    <div class="dropdown-menu header-auth-box" aria-labelledby="dropdownMenuLink">
                        @if(Auth::check())
                            {{--                            @if(Auth::user()->role_id >0 || Auth::user()->role == 'admin')--}}
                            <a class="dropdown-item admin" href="{{url('admin')}}">پنل مدیریت</a>
                            {{--                            @endif--}}
                        @else
                            <div class="text-center w-100">
                                <a class="btn btn-primary" href="{{url('login')}}">ورود به دیجی کالا</a>
                            </div>
                            <div class="register_link">
                                <span>کاربر جدید هستید؟</span>
                                <a class="link" href="{{url('register')}}">ثبت نام</a>
                            </div>

                        @endif
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item profile" href="{{url('profile')}}">
                            پروفایل
                        </a>
                        <a class="dropdown-item orders" href="{{url('profile/orders')}}">
                            پیگیری سفارشات
                        </a>
                        @if(Auth::check())
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="post" id="logout_form">@csrf</form>
                            <a class="dropdown-item logout">
                                خروج از حساب کاربری
                            </a>
                        @endif
                    </div>
                </div>
                <div class="header_divider"></div>
                <div class="cart_header_box">
                    <div class="btn_cart">
                        <p id="cart_product_count" data-counter="{{\App\Cart::getProductCount()}}">سبدخرید</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('Include.categoryList',['catList'=>$catList])

    <div class="container-fluid">
        @yield('content')
    </div>
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
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
    @yield('content')
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
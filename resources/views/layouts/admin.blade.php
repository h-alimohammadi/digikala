<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('link')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <title>پنل مدیریت</title>
</head>
<body>
<div class="container-fluid p-0">
    <div class="page_sidebar ">
        @php
            $sidebarMenu = [];
           $sidebarMenu[0]=[
               'label'=>'محصولات',
               'icon'=>'fa fa-shopping-cart',
               'access'=>'product|category',
               'child'=>[
                   ['url'=>url('admin/product'),'label'=>'مدیریت محصولات','access'=>'product'],
                   ['url'=>url('admin/product/create'),'label'=>'افزودن محصولات','access'=>'product','accessValue'=>0],
                   ['url'=>url('admin/category'),'label'=>'مدیریت دسته ها','access'=>'product'],
               ],
           ];
           $sidebarMenu[1]=[
               'label'=>' اسلایدر ها',
               'icon'=>'fa fa-sliders',
               'access'=>'sliders',
               'child'=>[
                   ['url'=>url('admin/slider'),'label'=>'مدیریت اسلایدر ها','access'=>'product'],
                   ['url'=>url('admin/slider/create'),'label'=>'افزودن اسلایدر','access'=>'product'],
               ],
           ];
           $sidebarMenu[2]=[
               'label'=>' مناطق',
               'icon'=>'fa fa-location-arrow',
               'access'=>'location',
               'child'=>[
                   ['url'=>url('admin/province'),'label'=>'مدیریت استان ها','access'=>'location'],
                   ['url'=>url('admin/city'),'label'=>'مدیریت شهر ها','access'=>'location'],
               ],
           ];
           $sidebarMenu[3]=[
               'label'=>' سفارشات',
               'icon'=>'fa fa-list',
               'access'=>'orders',
               'child'=>[
                   ['url'=>url('admin/orders'),'label'=>'مدیریت سفارشات','access'=>'orders','accessValue'=>0],
                   ['url'=>url('admin/orders/submission'),'label'=>'مدیریت مرسوله ها','access'=>'orders','accessValue'=>4],
                   ['url'=>url('admin/orders/submission/approved'),'label'=>'مرسوله های تایید شده','access'=>'orders','accessValue'=>5],
                   ['url'=>url('admin/orders/submission/items/today'),'label'=>'مرسوله های ارسالی امروز','access'=>'orders','accessValue'=>6],
                   ['url'=>url('admin/orders/submission/ready'),'label'=>'مرسوله های آماده ارسال','access'=>'orders','accessValue'=>7],
                   ['url'=>url('admin/orders/posting/send'),'label'=>'مرسوله های ارسال شده به پست','access'=>'orders','accessValue'=>8],
                   ['url'=>url('admin/orders/posting/receive'),'label'=>'مرسوله های آماده دریافت از پست','access'=>'orders','accessValue'=>9],
                   ['url'=>url('admin/orders/delivered/shipping'),'label'=>'مرسوله های تحویل داده شده','access'=>'orders','accessValue'=>10],
               ],
           ];
        @endphp
        <span class="fa fa-bars" id="sidebar_toggle"></span>
        <ul id="sidebar_menu">
            @foreach($sidebarMenu as $key=>$sidebar)
                @php($child=array_key_exists('child',$sidebar))
                <li>
                    <a @if(array_key_exists('url',$sidebar)) href="{{$sidebar['url']}}" @endif>
                        <span class="fa {{$sidebar['icon']}}"></span>
                        <span class="sidebar_menu_text">{{$sidebar['label']}}</span>
                        @if($child)
                            <span class="fa fa-angle-left"></span>
                        @endif

                    </a>
                    <div class="child_menu">
                        @if($child)
                            @foreach($sidebar['child'] as $key2=>$child)
                                <a href="{{$child['url']}}">{{$child['label']}}</a>
                            @endforeach
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="page_content">
        <div class="content_box" id="app">
            @yield('content')
        </div>
    </div>
</div>
<div class="message_div">
    <div class="message_box">
        <p id="msg"></p>
        <a class="alert alert-success" onclick="deleted_row()">بله</a>
        <a class="alert alert-danger" onclick="hide_box()">خیر</a>
    </div>
</div>

<div id="loading_box">
    <div class="loading_div">
        <div class="loading"></div>
        <span>در حال ارسال اطلاعات</span>
    </div>
</div>

<div class="server_error_box" id="server_error_box">
    <div>
        <span class="fa fa-warning"></span>
        <span id="message">خطا در ارسال درخواست، لطفا مجددا تلاش نمایید.</span>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/adminVue.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
@yield('script')
</body>
</html>
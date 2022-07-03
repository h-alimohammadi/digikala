<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('link')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <title>پنل مدیریت</title>
</head>
<body>
<div class="container-fluid p-0">
    <div class="page_sidebar ">
        <span class="fa fa-bars" id="sidebar_toggle"></span>
        <ul id="sidebar_menu">
            <li>
                <a>
                    <span class="fa fa-shopping-cart"></span>
                    <span class="sidebar_menu_text">محصولات</span>
                    <span class="fa fa-angle-left"></span>
                </a>
                <div class="child_menu">
                    <a href="{{url('admin/product')}}">مدیریت محصولات</a>
                    <a href="{{url('admin/product/create')}}">افزودن محصولات</a>
                    <a href="{{url('admin/category')}}">مدیریت دسته ها</a>
                </div>
            </li>
            <li>
                <a>
                    <span class="fa fa-sliders"></span>
                    <span class="sidebar_menu_text">اسلایدر</span>
                    <span class="fa fa-angle-left"></span>
                </a>
                <div class="child_menu">
                    <a href="#">مدیریت اسلایدر ها</a>
                    <a href="#">افزودن اسلایدر</a>
                </div>
            </li>
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
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
@yield('script')
</body>
</html>
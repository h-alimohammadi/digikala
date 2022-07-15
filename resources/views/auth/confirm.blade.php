@extends('layouts.auth')

@section('content')
    <div id="auth_box">
        <div class="auth_box_title">
            <span>تایید شماره تلفن همراه</span>
        </div>
        <div class="alert alert-success">
            <span>کد تایید برای شماره موبایل {{ Session::get('mobile_number') }} ارسال شد.  </span>
            <a href="{{ route('register') }}" class="data_link">ویرایش شماره</a>
        </div>
        <form method="POST" action="{{ route('active_account') }}" id="active_account_form">
            @csrf
            <div class="form-group p-4">
                <div class="field_name field_name_reg">کد تایید را وارد نمایید</div>
                <input type="hidden" value="{{ Session::get('mobile_number') }}" id="user_mobile" name="mobile">
                <div class="number_input_div">
                    <input type="text" name="active_code" maxlength="6" value="{{ old('active_code') }}"
                           class="number_input number form-control">
                </div>
                <div class="line_box">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
                <p id="resend_active_code">
                    <span>ارسال مجدد کد</span>
                    <span id="timer"></span>
                </p>

                @if(Session::has('validate_error'))
                    <p class="alert alert-danger">{{ Session::get('validate_error') }}</p>
                    @endif
                <div class="send_btn w-100" id="active_account_btn">
                    <span class="line"></span>
                    <span class="title">نهایی کردن ثبت نام</span>
                </div>
            </div>
        </form>
        <div class="alert alert-warning">
            <span>قبلا در سایت ثبت نام کرده اید؟</span>
            <span>
                <a class="data_link" href="{{ route('login') }}">وارد شوید</a>
            </span>
        </div>
    </div>

@endsection

@section('script')
    <script>
    start_timer();
    </script>
@endsection
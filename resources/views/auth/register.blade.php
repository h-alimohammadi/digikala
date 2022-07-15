@extends('layouts.auth')

@section('content')
    <div id="auth_box">
        <div class="auth_box_title">
            <span>ثبت نام در سایت</span>
        </div>
        <div style="margin: 25px;">
            <form method="POST" action="{{ route('register') }}" id="register_form">
                @csrf
                <div class="form-group">
                    <div class="field_name">شماره موبایل</div>
                    <label class="input_label user_name">
                        <input type="text" class="form-control @if($errors->has('mobile')) validate_error_border @endif"
                               name="mobile" value="{{ old('mobile') }}" id="register_mobile"
                               placeholder="شماره موبایل خود را وارد نمایید...">

                        <label id="mobile_error_message" class="feedback-hint"
                               @if($errors->has('mobile')) style="display: block;" @endif>
                            @if($errors->has('mobile'))
                                <span>{{ $errors->first('mobile') }}</span>
                            @endif
                        </label>
                    </label>
                </div>
                <div class="form-group">
                    <div class="field_name">کلمه عبور</div>
                    <label class="input_label user_pass">
                        <input type="password"
                               class="form-control @if($errors->has('mobile')) validate_error_border @endif"
                               name="password" value="{{ old('password') }}" id="register_password"
                               placeholder="کلمه عبور خود را وارد نمایید...">

                        <label id="password_error_message" class="feedback-hint"
                               @if($errors->has('password')) style="display: block;" @endif>
                            @if($errors->has('password'))
                                <span>{{ $errors->first('password') }}</span>
                            @endif
                        </label>
                    </label>
                </div>
                <div class="register_accept">
                    <label>
                        <span class="check_box active"></span>
                        <a class="data_link" href="">حریم خصوصی</a>
                        <span>و</span>
                        <a class="data_link" href="">شرایط و قوانین</a>
                        <span>استفاده از سرویس های سایت را مطالعه نموده و با کلیه موارد آن موافقم.</span>
                    </label>
                </div>
                <div class="send_btn register_btn w-100" id="register_btn">
                    <span class="line"></span>
                    <span class="title">ثبت نام در فروشگاه</span>
                </div>
            </form>
        </div>
        <div class="alert alert-warning">
            <span>قبلا در سایت ثبت نام کرده اید؟</span>
            <span>
                <a class="data_link" href="{{ route('login') }}">وارد شوید</a>
            </span>
        </div>
    </div>
@endsection

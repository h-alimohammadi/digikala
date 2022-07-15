<div id="auth_box">
    <div class="auth_box_title">
        <span>ورود به سایت </span>
    </div>
    <div style="margin: 25px;">
        <form method="POST" action="{{ route('login') }}" id="login_form">
            @csrf
            <div class="form-group">
                <div class="field_name">شماره موبایل</div>
                <label class="input_label user_name">
                    <input type="text" class="form-control @if($errors->has('mobile')) validate_error_border @endif"
                           name="mobile" value="{{ old('mobile') }}" id="login_mobile"
                           placeholder="شماره موبایل خود را وارد نمایید...">

                    <label id="mobile_error_message" class="feedback-hint"></label>
                </label>
            </div>
            <div class="form-group">
                <div class="field_name">کلمه عبور</div>
                <label class="input_label user_pass">
                    <input type="password" class="form-control @if($errors->has('mobile')) validate_error_border @endif"
                           name="password" value="{{ old('password') }}" id="login_password"
                           placeholder="کلمه عبور خود را وارد نمایید...">

                    <label id="password_error_message" class="feedback-hint"></label>
                </label>
            </div>
            @if($errors->has('mobile'))
                <div class="alert alert-danger">{{ $errors->first('mobile') }}</div>
            @endif

            <div class="send_btn login_btn w-100" id="login_btn">
                <span class="line"></span>
                <span class="title">ورود به سایت</span>
            </div>
            <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="check_box" id="login-remember"></span>
                        <label class="form-check-label" for="remember">
                            مرا به خاطر بسپار
                        </label>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="alert alert-warning">
        <span>کاربر جدید هستید؟</span>
        <span>
                <a class="data_link" href="{{ route('register') }}">وارد شوید</a>
            </span>
    </div>
</div>
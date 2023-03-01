<footer class="c-footer">
    <div class="row">
        <div class="col-6">
            <ul>
                <li>
                    <a href="">نحوه ثبت سفارش</a>
                </li>
                <li>
                    <a href="">رویه ارسال سفارش</a>
                </li>
                <li>
                    <a href="">شیوه های پرداخت</a>
                </li>
            </ul>
        </div>
        <div class="col-6">
            <ul>
                <li>
                    <a href="">پاسخ به پرسش های متداول</a>
                </li>
                <li>
                    <a href="">رویه های بازگرداندن کالا</a>
                </li>
                <li>
                    <a href="">شرایط استفاده</a>
                </li>
                <li>
                    <a href="">حریم خصوصی</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <h6 class="text-center w-100">از تخفیف ها و جدیدترین های {{env('SHOP_NAME','')}} باخبر شوید</h6>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="آدرس ایمیل خود را وارد کنید">
            <button class="btn btn-success">ارسال</button>
        </div>
    </div>
    <div class="row mojavez">
        <h6>مجوز های فروشگاه</h6>
        <div>
            <img src="{{asset('files/images/enamad.png')}}">
            <img src="{{asset('files/images/BPMLogo.png')}}">
        </div>
    </div>
    <p>برای استفاده از مطالب {{env('SHOP_NAME','')}}، داشتن «هدف غیرتجاری» و ذکر «منبع» کافیست. تمام حقوق اين
        وب‌سايت نیز برای شرکت نوآوران فن آوازه (فروشگاه آنلاین {{env('SHOP_NAME','')}}) است.</p>
</footer>
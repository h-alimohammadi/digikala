@extends('layouts.Shop')

@section('content')
    <div class="content" style="margin-top: 40px;">
        <div class="product_info" id="score_box">
            <form method="post" id="comment_form">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{asset('files/uploads/thumbnails/'.$product->image_url)}}">
                    </div>

                    <div class="col-md-8">
                        <div class="score_box_header">
                            <p class="title">{{ $product->title }}</p>
                            @if(!empty($product->ename) && $product->ename != null)<p>{{ $product->ename}}</p>@endif
                        </div>
                        <div class="row">
                            @php
                                $score_item1=['کیفیت ساخت','نوآوری','سهولت استفاده'];
                                $score_item2=['سهولت طراحی و ظاهر','امکانات و قابلیت ها','ارزش خرید به نسبت قیمت'];
                            @endphp
                            <div class="col-md-6">
                                @foreach($score_item1 as $item)
                                    <div class="rang_box">
                                        <label class="label">{{ $item  }}</label>
                                        <div class="range_slider_div" data-rate-title="معمولی">
                                            <span class="js-slider-step slider_step_two active_range_step"
                                                  data-rate-title="خیلی بد"></span>
                                            <span class="js-slider-step slider_step_three active_range_step"
                                                  data-rate-title="بد"></span>
                                            <span class="js-slider-step slider_step_four "
                                                  data-rate-title="معمولی"></span>
                                            <span class="js-slider-step slider_step_five " data-rate-title="خوب"></span>
                                            <span class="js-slider-step slider_step_six " data-rate-title="عالی"></span>
                                            <div class="active_range_slider"></div>
                                        </div>
                                        <input class="item_slider" type="range" min="0" max="4" value="2"
                                               name="score_item[]">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                @foreach($score_item2 as $item)
                                    <div class="rang_box">
                                        <label class="label">{{ $item  }}</label>
                                        <div class="range_slider_div" data-rate-title="معمولی">
                                            <span class="js-slider-step slider_step_two active_range_step"
                                                  data-rate-title="خیلی بد"></span>
                                            <span class="js-slider-step slider_step_three active_range_step"
                                                  data-rate-title="بد"></span>
                                            <span class="js-slider-step slider_step_four "
                                                  data-rate-title="معمولی"></span>
                                            <span class="js-slider-step slider_step_five " data-rate-title="خوب"></span>
                                            <span class="js-slider-step slider_step_six " data-rate-title="عالی"></span>
                                            <div class="active_range_slider"></div>
                                        </div>
                                        <input class="item_slider" type="range" min="0" max="4" value="2"
                                               name="score_item[]">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="score_comment_form">
                            <div class="form-group">
                                <div class="account_title">عنوان نظر شما(اجباری)</div>
                                <div class="input_label">
                                    <input type="text" class="form-control" name="title" id="comment_title" placeholder="عنوان نظر خود را وارد کنید...">
                                    <label id="comment_title_error_message" class="feedback-hint"></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 pr-0" >
                                    <div class="positive_item">نقاط قوت</div>
                                    <div class="input_add_point advantage">
                                        <input type="text" value="" id="advantage">
                                        <button type="button" ></button>

                                    </div>
                                    <div id="advantage_input_box" class="item_list"></div>
                                </div>
                                <div class="col-md-6 pl-0">
                                    <div class="negative_item negative">نقاط ضعف</div>
                                    <div class="input_add_point disadvantage">
                                        <input type="text" value="" id="disadvantage">
                                        <button type="button" ></button>
                                    </div>
                                    <div id="disadvantage_input_box"  class="item_list"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="account_title">متن نظر شما(اجباری)</div>
                                <label class="input_label">
                                    <textarea  class="form-control" name="content" id="comment_content" placeholder="متن نظر خود را وارد کنید..."></textarea>
                                    <label id="comment_content_error_message" class="feedback-hint"></label>
                                </label>
                            </div>
                            <div class="form-group">
                                <button class="add_comment_btn">ثبت نظر</button>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="add_comment_tozihat">
                            <h4>
                                دیگران را با نوشتن نظرات خود، برای انتخاب این محصول راهنمایی کنید.
                            </h4>
                            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ.</p>
                            <p>لورم ایپسوم متن ساختگی سادگی نامفهوم از صنعت چاپ.</p>
                            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ. لورم ایپسوم متن ساختگی  چاپ</p>
                            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ.</p>
                            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ.</p>
                            <p>لورم ایپسوم متن ساختگی با تولید سادگی .لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ </p>
                            <p>لورم ایپسوم متنلورم ایپسوم متن ساختگی با چاپ ساختگی با تولید سادگی نامفهوم از صنعت چاپ.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
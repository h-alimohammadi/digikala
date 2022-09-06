@extends('layouts.order')
@section('content')
    <div class="order_header">
        <img src="{{asset('files/images/shop_icon.jpg')}}" class="shop_icon">
        <ul class="checkout_steps">
            <li>
                <a class="checkout_step">
                    <div class="step_item active_item" step-title="اطلاعات ارسال"></div>
                </a>
            </li>
            <li class="active">
                <a class="checkout_step">
                    <div class="step_item active_item" step-title="پرداخت"></div>
                </a>
            </li>
            <li class="active">
                <a class="checkout_step">
                    <div class="step_item active_item" step-title="اتمام خرید و ارسال"></div>
                </a>
            </li>
        </ul>
    </div>
    <div class="container-fluid">
        @php use App\Lib\Jdf;$jdf=new Jdf();$orderStatus=\App\Order::orderStatus();  @endphp
        <div class="profile_menu">
            <span class="profile_menu_title">
                جزئیات سفارش : {{replace_number($order->order_id)}}
            </span>
            <span class="profile_menu_title" style="padding: 0 20px 0;font-size: 12px">
                تاریخ ثبت سفارش : {{ $jdf->jdate(' j F Y',$order->created_att) }}
            </span>
            @if(isset($error_payment))
                <div class="alert alert-warning payment_warning">
                    <span>    پرداخت انجام نشد.</span>
                    در صورت کسر پول از حساب شما تا 15 دقیقه دیگر پول به حساب شما بر میگردد
                </div>
            @endif

            <table class="table table-bordered order_table_info">
                <tr>
                    <td>
                        تحویل گیرنده :
                        <span>{{$order->getAddress->name}}</span>
                    </td>
                    <td>
                        شماره تماس تحویل گیرنده :
                        <span>{{replace_number($order->getAddress->mobile)}}</span>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        آدرس تحویل گیرنده :
                        <span>{{$order->getAddress->province->name .' '.$order->getAddress->city->name.' '.$order->getAddress->address}}</span>
                    </td>
                    <td>
                        تعداد مرسوله :
                        <span>{{replace_number(replace_number(sizeof($order->getOrderInfo)))}}</span>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        مبلغ پرداخت شده :
                        <span>{{replace_number(number_format($order->price))}} تومان </span>
                    </td>
                    <td>
                        مبلغ کل :
                        <span>{{replace_number(number_format($order->total_price))}} تومان </span>
                    </td>
                </tr>
                @if(!empty($order->gift_value) && $order->gift_value >0)
                    <tr>
                        <td>
                            مبلغ کارت هدیه :
                            <span>{{replace_number(number_format($order->gift_value))}} تومان </span>
                        </td>
                        <td>
                            کد کارت هدیه :
                            <span>{{replace_number($order->getGiftCart->code)}}</span>
                        </td>
                    </tr>
                @endif
                @if(!empty($order->discount_value) && $order->discount_value >0)
                    <tr>
                        <td>
                            مبلغ کد تخفیف :
                            <span>{{replace_number(number_format($order->discount_value))}} تومان </span>
                        </td>
                        <td>
                            کد تخفیف :
                            <span>{{replace_number($order->discount_code)}}</span>
                        </td>
                    </tr>
                @endif
            </table>
            @foreach($order->getOrderInfo as $key=>$value)
                <div class="order_info_div">
                    <div class="header">
                        {{\App\Order::getOrderStatus($value->send_status,$orderStatus)}}
                    </div>
                    @if($value['send_status']==0 &&  $order->pay_status == 'Ok') @php $value['send_status']=1; @endphp  @endif
                    <div class="swiper-container order_steps" dir="rtl">
                        <div class="swiper-wrapper">
                            @foreach($orderStatus as $key2=>$status)
                                @if($key2>-1)
                                    <div class="swiper-slide">
                                        <div class="step_div @if($value['send_status'] < $key2) step_inactive  @endif">
                                            <img src="{{ url('files/images/step'.$key2.'.svg') }}"
                                                 @if($key2==6) style="width: 64%" @endif>
                                            <span>{{ $status }}</span>
                                        </div>
                                        @if($key2<6)
                                            <hr class="@if($value['send_status'] > $key2) hr_active  @endif">
                                        @else
                                            <div style="min-width: 66px"></div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <table class="table table-bordered order_table_info">
                        <tr>
                            <td>
                                کد مرسوله :
                                <span>{{replace_number($value['id'])}}</span>
                            </td>
                            <td>
                                زمان تحویل :
                                <span>{{replace_number($value['delivery_order_interval'])}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                نحوه ارسال :
                                <span>پست پیشتاز با ضرفیت اختصاصی برای دیجی انلاین</span>
                            </td>
                            <td>
                                هزینه ارسال :
                                <span>
                                    @if($value['send_order_amount']==0)
                                        رایگان
                                    @else
                                        {{replace_number(number_format($value['send_order_amount']))}}
                                    @endif

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-center">
                                مبلغ این مرسوله :
                                <span>
                                    {{ replace_number(number_format($order_data['order_row_amount'][$value->id])) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    <table class="table product_list_data">
                        <tr>
                            <th>نام محصول</th>
                            <th>تعداد</th>
                            <th>قیمت واحد</th>
                            <th>قیمت کل</th>
                            <th>تخفیف</th>
                            <th>قیمت نهایی</th>
                        </tr>
                        @foreach($order_data['row_data'][$value->id] as $key2=>$product)
                            <tr>
                                <td class="product__info">
                                    <div>
                                        <img src="{{ url('files/uploads/thumbnails/'.$product['image_url']) }}">
                                        <ul>
                                            <li class="title">
                                                {{$product['title']}}
                                            </li>
                                            @if($product['color_id'] > 0)
                                                <li>
                                                    <span>رنگ :</span>
                                                    <span>{{$product['color_name']}}</span>
                                                </li>
                                            @endif
                                            <li>
                                                <span>گارانتی :</span>
                                                <span>{{$product['warranty_name']}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>{{replace_number($product['product_count'])}}</td>
                                <td>{{replace_number(number_format($product['product_price1']))}} تومان</td>
                                <td>{{replace_number(number_format($product['product_price1']*$product['product_count']))}}
                                    تومان
                                </td>
                                <td>
                                    @php($discount=(($product['product_price1']*$product['product_count'])-($product['product_price2']*$product['product_count'])))
                                    {{replace_number(number_format($discount))}} تومان
                                </td>
                                <td>{{replace_number(number_format($product['product_price2']*$product['product_count']))}}
                                    تومان
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('link')
    <link href="{{ asset('css/swiper-bundle.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{asset('js/swiper.min.js')}}" type="text/javascript"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 5,
            spaceBetween: 0,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
    </script>

@endsection
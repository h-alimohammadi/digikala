@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
     ['title'=>'مدیریت مرسوله ها','url'=>url('admin/orders/submission')],
    ['title'=>'جزئیات مرسوله','url'=>url('admin/orders/submission/'.$submissionInfo->id)],
    ]])
    <div class="panel">
        <div class="header">
            جزئیات مرسوله {{ $submissionInfo->id }}
        </div>
        @php use App\Lib\Jdf;$jdf=new Jdf();$orderStatus=\App\Order::orderStatus();  @endphp

        <div class="panel_content">
            <table class="table table-bordered order_table_info">
                <tr>
                    <td>
                        تحویل گیرنده :
                        <span>{{$submissionInfo->getOrder->getAddress->name}}</span>
                    </td>
                    <td>
                        شماره تماس تحویل گیرنده :
                        <span>{{replace_number($submissionInfo->getOrder->getAddress->mobile)}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        آدرس تحویل گیرنده :
                        <span>{{$submissionInfo->getOrder->getAddress->province->name .' '.$submissionInfo->getOrder->getAddress->city->name.' '.$submissionInfo->getOrder->getAddress->address}}</span>
                    </td>
                    <td>
                        شماره سفارش :
                        <span>{{replace_number($submissionInfo->getOrder->order_id)}}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        مبلغ پرداخت شده :
                        <span>{{replace_number(number_format($submissionInfo->getOrder->price))}} تومان </span>
                    </td>
                    <td>
                        مبلغ کل :
                        <span>{{replace_number(number_format($submissionInfo->getOrder->total_price))}} تومان </span>
                    </td>
                </tr>
            </table>
            <div class="order_info_div w-100">
                <div class="header">
                    {{\App\Order::getOrderStatus($submissionInfo->send_status,$orderStatus)}}
                </div>

                <order-step :steps="{{ json_encode($orderStatus) }}" :send_status="{{ $submissionInfo->send_status }}" :order_id="{{ $submissionInfo->id }}"></order-step>

                <table class="table table-bordered order_table_info">
                    <tr>
                        <td>
                            کد مرسوله :
                            <span>{{replace_number($submissionInfo->id)}}</span>
                        </td>
                        <td>
                            زمان تحویل :
                            <span>{{replace_number($submissionInfo->delivery_order_interval)}}</span>
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
                                    @if($submissionInfo->send_order_amount ==0)
                                    رایگان
                                @else
                                    {{replace_number(number_format($submissionInfo->send_order_amount))}}
                                @endif

                                </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            مبلغ این مرسوله :
                            <span>
                                    {{ replace_number(number_format($order_data['order_row_amount'][$submissionInfo->id])) }}
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
                    @foreach($order_data['row_data'][$submissionInfo->id] as $key2=>$product)
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
        </div>
    </div>
@endsection
@section('link')
    <link href="{{ asset('css/swiper-bundle.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{asset('js/swiper.min.js')}}" type="text/javascript"></script>
    <script>
        // $("#sidebar_toggle").click();
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
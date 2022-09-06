@extends('layouts.Shop')

@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('Include.user_panel_menu',['active'=>'orders'])
        </div>
        <div class="col-md-9" style="padding-right: 0;">
            <div class="profile_menu">
                <span class="profile_menu_title">سفارشات من</span>
                <table class="table product_list_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>شماره سفارش</th>
                        <th>تاریخ ثبت سفارش</th>
                        <th>مبلغ قابل پرداخت</th>
                        <th>مبلغ کل</th>
                        <th>عملیات پرداخت</th>
                        <th>جزئیات</th>
                    </tr>
                    </thead>
                    <tbody>

                    @php $jdf=new \App\Lib\Jdf() ;$i=(isset($_GET['page']) ? ($_GET['page']-1)*10+1 : 1 );
;@endphp
                    @foreach($orders as $key=>$value)
                        {{--                        @php--}}
                        {{--                            $e=explode('-',explode(' ',$value->created_at)[0]);--}}
                        {{--                            $d=$jdf->gregorian_to_jalali($e[0],$e[1],$e[2],'/');--}}
                        {{--                        @endphp--}}
                        <tr>
                            <td>{{ replace_number($i) }}</td>
                            <td>{{replace_number($value->order_id)}}</td>
                            <td>{{ $jdf->jdate('j F Y',$value->created_at) }}</td>
                            <td>{{ replace_number(number_format($value->price)) }} تومان</td>
                            <td>{{ replace_number(number_format($value->total_price)) }} تومان</td>
                            <td>
                                @if($value->pay_status == 'awaiting_payment')
                                    در انتظار پرداخت
                                @elseif($value->pay_status == 'Ok')
                                    پرداخت شده
                                @elseif($value->pay_status == 'canceled')
                                    لغو شده
                                @else
                                    خطا در اتصال به درگاه
                                @endif
                            </td>
                            <td>
                                <a href="{{url('user/profile/orders/'.$value->id)}}">
                                    <span class="fa fa-angle-left"></span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
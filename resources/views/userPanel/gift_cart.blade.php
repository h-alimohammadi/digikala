@extends('layouts.Shop')

@section('content')
    <div class="row">
        <div class="col-md-3">
            @include('Include.user_panel_menu',['active'=>'gift_cart'])
        </div>
        <div class="col-md-9" style="padding-right: 0;">
            <div class="profile_menu">
                <span class="profile_menu_title">کارتهای هدیه من</span>
                <table class="table product_list_table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>کد کارت</th>
                        <th>اعتبار کارت هدیه</th>
                        <th>اعتبار مصرف شده</th>
                        <th>تاریخ ثبت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $jdf=new \App\Lib\Jdf() @endphp
                    @foreach($giftCart as $key=>$value)
                        @php
                            $e=explode('-',explode(' ',$value->created_at)[0]);
                            $d=$jdf->gregorian_to_jalali($e[0],$e[1],$e[2],'/');
                        @endphp
                        <tr>
                            <td>{{ replace_number(++$key) }}</td>
                            <td>{{ replace_number($value->code) }}</td>
                            <td>{{replace_number(number_format($value->credit_cart))}} تومان</td>
                            <td>{{replace_number(number_format($value->credit_used))}} تومان</td>
                            <td>{{replace_number($d)}} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $giftCart->links() }}
            </div>
        </div>
    </div>
@endsection
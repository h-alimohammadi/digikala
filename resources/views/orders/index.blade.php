@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت سفارشات','url'=>url('admin/orders')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت سفارشات

            @include('Include.item_table',['count'=>$trashed_order_count,'route'=>'admin/orders','title'=>'سفارش'])
        </div>
        <div class="panel_content">
            @include('Include.alert')
            <form method="get" class="search_form order_search">
                <div class="btn-group">
                    @if(isset($_GET['trashed']))
                        <input type="hidden" name="trashed" value="trashed">
                    @endif
                    <input type="text" autocomplete="off" name="order_id" class="form-control" value="{{$request->get('order_id','')}}" placeholder="شماره سفارش را وارد کنید...">
                    <input type="text" autocomplete="off" name="first_date" class="form-control pdate mr-2" id="pcal1" value="{{$request->get('first_date','')}}" placeholder="شروع از تاریخ">
                    <input type="text" autocomplete="off" name="end_date" class="form-control pdate mr-2 ml-2" id="pcal2" value="{{$request->get('end_date','')}}" placeholder="تا تاریخ">
                    <button class="btn btn-primary">جست و جو</button>
                </div>
            </form>
            <form method="post" id="data_form">
                @csrf
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ردیف</th>
                        <th>شماره سفارش</th>
                        <th>زمان ثبت</th>
                        <th>مبلغ سفارش</th>
                        <th>وضعیت سفارش</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*10+1 : 1 );
                    $jdf = new \App\Lib\Jdf();
                    @endphp
                    @foreach($orders as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_category[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td @if($value->order_read=='no') style="color: red" @endif>{{replace_number($value->order_id)}}</td>
                            <td>{{ $jdf->jdate('H:i:s',$value->created_at) }}
                                / {{ $jdf->jdate(' j F Y',$value->created_at) }}</td>
                            <td>
                                <span class="alert alert-primary" style="padding: 5px 10px;border-radius: 0">
                                    {{ replace_number(number_format($value->price)) }} تومان
                                </span>
                            </td>
                            <td>
                                @if($value->pay_status == 'awaiting_payment')
                                    <span class="alert alert-warning" style="padding: 5px 10px;border-radius: 0">در انتظار پرداخت</span>
                                @elseif($value->pay_status == 'Ok')
                                    <span class="alert alert-success" style="padding: 5px 10px;border-radius: 0">پرداخت شده</span>
                                @elseif($value->pay_status == 'canceled')
                                    <span class="alert alert-warning" style="padding: 5px 10px;border-radius: 0">لغو شده</span>
                                @else
                                    <span class="alert alert-danger" style="padding: 5px 10px;border-radius: 0">خطا در اتصال به درگاه</span>
                                @endif
                            </td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="نمایش سفارش" href="{{url('admin/orders/'.$value->id)}}">
                                        <span class="fa fa-eye"></span>
                                    </a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی سفارش"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/orders/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این سفارش مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف سفارش"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/orders/'.$value->id)}}','{{Session::token()}}','آیا از حذف این سفارش مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف سفارش برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/orders/'.$value->id)}}','{{Session::token()}}','آیا از حذف این سفارش مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($orders)==0)
                        <tr>
                            <td colspan="7">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $orders->links() }}
        </div>
    </div>
@endsection
@section('link')
    <link rel="stylesheet" href="{{asset('css/js-persian-cal.css')}}"/>
@endsection
@section('script')
    <script src="{{asset('js/js-persian-cal.min.js')}}"></script>
    <script>
        var pcal1 = new AMIB.persianCalendar('pcal1');
        var pcal2 = new AMIB.persianCalendar('pcal2');
    </script>
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
@endsection

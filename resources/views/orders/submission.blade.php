@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>$label,'url'=>url('admin/orders/'.$label_url)],
    ]])
    <div class="panel">
        <div class="header">
            {{$label}}
        </div>
        <div class="panel_content">
            @include('Include.alert')
            <form method="get" class="search_form order_search">
                <div class="btn-group">
                    <input type="text" autocomplete="off" name="submission_id" class="form-control"
                           value="{{$request->get('submission_id','')}}" placeholder="شماره مرسوله...">
                    <button class="btn btn-primary">جست و جو</button>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ردیف</th>
                    <th>کد مرسوله</th>
                    <th>تاریخ ثبت</th>
                    <th>تعداد کالا</th>
                    <th>وضعیت مرسوله</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $orderStatus=\App\Order::orderStatus();
                    $jdf =new \App\Lib\Jdf();
                    $i=(isset($_GET['page']) ? ($_GET['page']-1)*15+1 : 1 );
                @endphp
                @foreach($submission as $key=>$value)
                    @php
                        $e =explode(' ',$value->created_at);
                        $e2 =explode('-',$e[0]);
                    @endphp
                    <tr>
                        <td>{{replace_number($i)}}</td>
                        <td>{{replace_number($value->id)}}</td>
                        <td>{{replace_number($jdf->gregorian_to_jalali($e2[0],$e2[1],$e2[2],'-'))}}</td>
                        <td>{{replace_number(getOrderProductCount($value->product_id))}}</td>
                        <td>
                            @if(array_key_exists($value->send_status,$orderStatus))
                                {{$orderStatus[$value->send_status]}}
                            @endif
                        </td>
                        <td>
                            <a data-toggle="tooltip" data-placement="bottom" title="جزئیات مرسوله" href="{{url('admin/orders/submission/'.$value->id)}}">
                                <span class="fa fa-eye"></span>
                            </a>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp

                @endforeach
                @if(sizeof($submission)==0)
                    <tr>
                        <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                    </tr>
                @endif
                </tbody>
            </table>
            {{ $submission->links() }}
        </div>
    </div>
@endsection
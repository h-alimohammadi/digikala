@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
       ['title'=>'مدیریت کدهای تخفیف','url'=>url('admin/discount')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت کدهای تخفیف
            @include('Include.item_table',['count'=>$trashed_disc_count,'route'=>'admin/discount','title'=>'کد تخفیف'])
        </div>
        <div class="panel_content">
            @include('Include.alert')
            <form method="get" class="search_form">
                <div class="btn-group">
                    @if(isset($_GET['trashed']))
                        <input type="hidden" name="trashed" value="trashed">
                    @endif
                    <input type="text" name="string" class="form-control" value="{{$request->get('string','')}}"
                           placeholder="کلمه مورد نظر را وارد کنید...">
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
                        <th>کد تخفیف</th>
                        <th>میزان تخفیف</th>
                        <th>تاریخ انقضاء</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*10+1 : 1 );
                    $jdf = new \App\Lib\Jdf();
                    @endphp
                    @foreach($discounts as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_discount[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>{{$value->code}}</td>
                            <td>
                                @if(!empty($value->amount_discount))
                                    {{ replace_number(number_format($value->amount)) }} تومان
                                    @else
                                    {{ replace_number($value->amount_percent) }} درصد
                                @endif
                            </td>
                            <td>{{ $jdf->jdate('Y/n/j',$value->expire_time) }}</td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش کد تخفیف"
                                       href="{{url('admin/discount/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی کد تخفیف"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/discount/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این کد تخفیف مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف کد تخفیف"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/discount/'.$value->id)}}','{{Session::token()}}','آیا از حذف این کد تخفیف مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف کد تخفیف برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/discount/'.$value->id)}}','{{Session::token()}}','آیا از حذف این کد تخفیف مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($discounts)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $discounts->links() }}
        </div>
    </div>
@endsection
@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت گارانتی ها','url'=>url('admin/warranty')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت گارانتی ها

            @include('Include.item_table',['count'=>$trashed_warr_count,'route'=>'admin/warranty','title'=>'گارانتی'])
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
                        <th>نام گارانتی</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*5+1 : 1 );
                    @endphp
                    @foreach($warranties as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_warranty[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>{{$value->name}}</td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش گارانتی"
                                       href="{{url('admin/warranty/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی گارانتی"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/warranty/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این گارانتی مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف گارانتی"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/warranty/'.$value->id)}}','{{Session::token()}}','آیا از حذف این گارانتی مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف گارانتی برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/warranty/'.$value->id)}}','{{Session::token()}}','آیا از حذف این گارانتی مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($warranties)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $warranties->links() }}
        </div>
    </div>
@endsection
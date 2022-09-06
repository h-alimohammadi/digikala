@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت دسته ها','url'=>url('admin/city')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت شهر ها

            @include('Include.item_table',['count'=>$trashed_city_count,'route'=>'admin/city','title'=>'شهر'])
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
                        <th>نام شهر</th>
                        <th>استان</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*10+1 : 1 );
                    @endphp
                    @foreach($citys as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_city[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>{{$value->name}}</td>
                            <td>{{$value->province->name}}</td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش شهر"
                                       href="{{url('admin/city/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی شهر"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/city/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این شهر مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف شهر"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/city/'.$value->id)}}','{{Session::token()}}','آیا از حذف این شهر مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف شهر برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/city/'.$value->id)}}','{{Session::token()}}','آیا از حذف این شهر مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($citys)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $citys->links() }}
        </div>
    </div>
@endsection
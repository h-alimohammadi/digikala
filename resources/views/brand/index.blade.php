@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت برند ها','url'=>url('admin/brand')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت برند ها

            @include('Include.item_table',['count'=>$trashed_brand_count,'route'=>'admin/brand','title'=>'برند'])
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
                        <th>ایکن برند</th>
                        <th>نام برند</th>
                        <th>نام انگلیسی برند</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*5+1 : 1 );
                    @endphp
                    @foreach($brands as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_brand[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>
                                <img width="50px"
                                     src="{{$value->icon != null ?asset('files/uploads/brand/'.$value->icon) :asset('files/images/pic_1.jpg')}}">
                            </td>

                            <td>{{$value->name}}</td>

                            <td>{{$value->ename}}</td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش برند"
                                       href="{{url('admin/brand/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی برند"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/brand/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این برند مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف برند"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/brand/'.$value->id)}}','{{Session::token()}}','آیا از حذف این برند مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف برند برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/brand/'.$value->id)}}','{{Session::token()}}','آیا از حذف این برند مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($brands)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $brands->links() }}
        </div>
    </div>
@endsection
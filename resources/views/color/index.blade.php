@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت رنگ ها','url'=>url('admin/color')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت رنگ ها

            @include('Include.item_table',['count'=>$trashed_color_count,'route'=>'admin/color','title'=>'رنگ'])
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
                        <th>نام رنگ</th>
                        <th>کد رنگ</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*5+1 : 1 );
                    @endphp
                    @foreach($colors as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_color[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>

                            <td>{{$value->name}}</td>
                            <td style="background: {{$value->code}};color :{{$value->name == 'مشکی' ? 'white' : ""}}">{{$value->code}}</td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش رنگ"
                                       href="{{url('admin/color/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی رنگ"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/color/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این رنگ مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف رنگ"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/color/'.$value->id)}}','{{Session::token()}}','آیا از حذف این رنگ مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف رنگ برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/color/'.$value->id)}}','{{Session::token()}}','آیا از حذف این رنگ مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($colors)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $colors->links() }}
        </div>
    </div>
@endsection
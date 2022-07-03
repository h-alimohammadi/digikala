@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت اسلایدر ها','url'=>url('admin/slider')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت برند ها

            @include('Include.item_table',['count'=>$trashed_slider_count,'route'=>'admin/slider','title'=>'اسلایدر'])
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
                        <th>عنوان اسلایدر</th>
                        <th>تصویر</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*5+1 : 1 );
                    @endphp
                    @foreach($sliders as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_slider[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>{{$value->title}}</td>
                            <td>
                                <img width="400px" height="130" src="{{$value->image_url != null ?asset('files/uploads/slider/'.$value->image_url) :asset('files/images/pic_1.jpg')}}">
                            </td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش اسلایدر"
                                       href="{{url('admin/slider/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی اسلایدر"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/slider/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این اسلایدر مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف اسلایدر"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/slider/'.$value->id)}}','{{Session::token()}}','آیا از حذف این اسلایدر مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف اسلایدر برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/slider/'.$value->id)}}','{{Session::token()}}','آیا از حذف این اسلایدر مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($sliders)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $sliders->links() }}
        </div>
    </div>
@endsection
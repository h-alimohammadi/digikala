@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت دسته ها','url'=>url('admin/category')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت دسته ها

            @include('Include.item_table',['count'=>$trashed_cat_count,'route'=>'admin/category','title'=>'دسته'])
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
                        <th>نام دسته</th>
                        <th>دسته والد</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*10+1 : 1 );
                    @endphp
                    @foreach($categories as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_category[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>{{$value->name}}</td>
                            <td>{{$value->getParent->name}}</td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش دسته"
                                       href="{{url('admin/category/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی دسته"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/category/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این دسته مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف دسته"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/category/'.$value->id)}}','{{Session::token()}}','آیا از حذف این دسته مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف دسته برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/category/'.$value->id)}}','{{Session::token()}}','آیا از حذف این دسته مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($categories)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $categories->links() }}
        </div>
    </div>
@endsection
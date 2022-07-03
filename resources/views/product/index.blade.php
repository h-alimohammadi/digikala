@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت محصولات','url'=>url('admin/product')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت محصولات

            @include('Include.item_table',['count'=>$trashed_product_count,'route'=>'admin/product','title'=>'محصول'])
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
                        <th>تصویر محصول</th>
                        <th>عنوان</th>
                        <th>فروشنده</th>
                        <th>وضعیت محصول</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*10+1 : 1 );
                    @endphp
                    @foreach($products as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_product[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>
                                <img class="product_pic" src="{{asset('files/uploads/thumbnails/'.$value->image_url)}}">
                            </td>
                            <td>{{$value->title}}</td>
                            <td>--</td>
                            <td>
                                @if(array_key_exists($value->status,$status))
                                    <span class="alert @if($value->status == 1) alert-success @else alert-danger @endif" style="font-size: 13px;padding: 5px 7px;">{{$status[$value->status]}}</span>
                                @endif
                            </td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش محصول"
                                       href="{{url('admin/product/'.$value->id.'/edit')}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی محصول"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/product/'.$value->id)}}','{{Session::token()}}','آیا از بازیابی این محصول مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف محصول"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/product/'.$value->id)}}','{{Session::token()}}','آیا از حذف این محصول مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف محصول برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/product/'.$value->id)}}','{{Session::token()}}','آیا از حذف این محصول مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($products)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $products->links() }}
        </div>
    </div>
@endsection
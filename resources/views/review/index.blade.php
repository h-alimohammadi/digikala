@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
   ['title'=>'مدیریت نقد و بررسی ها','url'=>url('admin/product/review?product_id='.$product->id)],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت نقد و بررسی ها( {{ $product->title }} )

            @include('Include.item_table',['count'=>$trashed_reviews_count,'route'=>'admin/product/review','title'=>'نقد و بررسی','queryString'=>['param'=>'product_id','value'=>$product->id]])
        </div>
        <div class="panel_content">
            @include('Include.alert')
            <a href="{{ url('admin/product/review/primary?product_id='.$product->id) }}" class="btn btn-primary">افزودن توضیحات اولیه</a>
            <form method="post" id="data_form" class="mt-3">
                @csrf
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ردیف</th>
                        <th>عنوان نقد و برسی</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*5+1 : 1 );
                    @endphp
                    @foreach($reviews as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_product/review[]" class="check_box" value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>{{$value->title}}</td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش نقد و برسی"
                                       href="{{url('admin/product/review/'.$value->id.'/edit?product_id='.$product->id)}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی نقد و برسی"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/product/review/'.$value->id.'?product_id='.$product->id)}}','{{Session::token()}}','آیا از بازیابی این نقد و برسی مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف نقد و برسی"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/product/review/'.$value->id.'?product_id='.$product->id)}}','{{Session::token()}}','آیا از حذف این نقد و برسی مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom"
                                          title="حذف نقد و برسی برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/product/review/'.$value->id.'?product_id='.$product->id)}}','{{Session::token()}}','آیا از حذف این نقد و برسی مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($reviews)==0)
                        <tr>
                            <td colspan="6">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
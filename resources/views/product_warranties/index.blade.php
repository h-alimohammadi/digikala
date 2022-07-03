@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت تنوع های قیمت','url'=>url('admin/product_warranties?product_id='.$product->id)],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت تنوع های قیمت - ({{ $product->title  }})

            @include('Include.item_table',['count'=>$trashed_pw_count,'route'=>"admin/product_warranties",'title'=>'تنوع قیمت','queryString'=>['param'=>'product_id','value'=>$product->id]])
        </div>
        <div class="panel_content">
            @include('Include.alert')
            {{--            <form method="get" class="search_form">--}}
            {{--                <div class="btn-group">--}}
            {{--                    @if(isset($_GET['trashed']))--}}
            {{--                        <input type="hidden" name="trashed" value="trashed">--}}
            {{--                    @endif--}}
            {{--                    <input type="text" name="string" class="form-control" value="{{$request->get('string','')}}"--}}
            {{--                           placeholder="کلمه مورد نظر را وارد کنید...">--}}
            {{--                    <button class="btn btn-primary">جست و جو</button>--}}
            {{--                </div>--}}
            {{--            </form>--}}
            <form method="post" id="data_form" id="product_warranties">
                @csrf
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ردیف</th>
                        <th>نام گارانتی</th>
                        <th>قیمت محصول</th>
                        <th>قیمت محصول برای فروش</th>
                        <th>تعداد موجودی محصول</th>
                        <th>رنگ</th>
                        <th>فروشنده</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i=(isset($_GET['page']) ? ($_GET['page']-1)*5+1 : 1 );
                    @endphp
                    @foreach($productWarranty as $key=>$value)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_product_warranties[]" class="check_box"
                                       value="{{$value->id}}">
                            </td>
                            <td>{{replace_number($i)}}</td>
                            <td>{{$value->warranty->name}}</td>
                            <td style="min-width: 160px"><span class="alert alert-success">{{replace_number(number_format($value->price1))}}  تومان </span>
                            </td>
                            <td style="min-width: 160px"><span class="alert alert-warning">{{replace_number(number_format($value->price2))}}  تومان </span>
                            </td>
                            <td>{{replace_number($value->product_number)}}</td>
                            <td>
                                @if($value->color->name != null)
                                    <div style="background: {{$value->color->code}};padding: 5px;border-radius: 5px;font-size: 14px">
                                        <span style="@if($value->color->name != 'سفید')color: white; @endif">{{$value->color->name}}</span>
                                    </div>
                                @endif
                            </td>
                            <td></td>
                            <td>
                                @if(!$value->trashed())
                                    <a data-toggle="tooltip" data-placement="bottom" title="ویرایش تنوع قیمت"
                                       href="{{url('admin/product_warranties/'.$value->id.'/edit?product_id='.$product->id)}}"><span
                                                class="fa fa-edit"></span></a>
                                @endif
                                @if($value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="بازیابی تنوع قیمت"
                                          class="fa fa-refresh"
                                          onclick="restore_row('{{url('admin/product_warranties/'.$value->id.'?product_id='.$product->id)}}','{{Session::token()}}','آیا از بازیابی این تنوع قیمت مطمئن هستید ؟')"></span>
                                @endif
                                @if(!$value->trashed())
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف تنوع قیمت"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/product_warranties/'.$value->id.'?product_id='.$product->id)}}','{{Session::token()}}','آیا از حذف این تنوع قیمت مطمئن هستید ؟')"></span>
                                @else
                                    <span data-toggle="tooltip" data-placement="bottom" title="حذف تنوع قیمت برای همیشه"
                                          class="fa fa-remove"
                                          onclick="del_row('{{url('admin/product_warranties/'.$value->id.'?product_id='.$product->id)}}','{{Session::token()}}','آیا از حذف این تنوع قیمت مطمئن هستید ؟')"></span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp

                    @endforeach
                    @if(sizeof($productWarranty)==0)
                        <tr>
                            <td colspan="9">رکوردی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </form>
            {{ $productWarranty->links() }}
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#sidebar_toggle").click();
    </script>
@endsection
@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت محصولات','url'=>url('admin/product')],
    ['title'=>'ثبت فیلتر','url'=>url('admin/product/'.$product->id.'/filters')],
    ]])
    <div class="panel">
        <div class="header">
            افزودن فیلتر برای محصول - ( {{ $product->title }} )
        </div>
        <div class="panel_content" id="product_filter_box">
            @include('Include.alert')
            @if(sizeof($productFilters)>0)
                <form method="post" id="product_filter_form" action="{{url('admin/product/'.$product->id.'/filters')}}">
                    @csrf
                    @foreach($productFilters as $value)
                        <div class="item_group" style="margin-bottom: 20px">
                            <p class="title">{{$value->title}}</p>
                            @foreach($value->getChild as $value2)
                                <div class="form-group">
                                    <input type="checkbox" @if(is_selected_filter($value->getValue,$value2->id)) checked="checked" @endif name="filter_value[{{$value->id}}][]" value="{{$value2->id}}">
                                    <label class="mr-2">{{$value2->title}}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <button class="btn btn-success">ثبت اطلاعات</button>
                </form>
            @else

            @endif
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.category_items').sortable();
            $('.child_item_box').sortable();
        });
    </script>
@endsection
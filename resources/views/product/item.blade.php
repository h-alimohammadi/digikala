@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت محصولات','url'=>url('admin/product')],
    ['title'=>'ثبت مشخصات فنی','url'=>url('admin/product/'.$product->id.'/items')],
    ]])
    <div class="panel">
        <div class="header">
            افزودن مشخصات فنی برای محصول - ( {{ $product->title }} )
        </div>
        <div class="panel_content">
            @include('Include.alert')
            @if(sizeof($productItems)>0)
                <form method="post" id="product_items_form" action="{{url('admin/product/'.$product->id.'/items')}}">
                    @csrf
                    @foreach($productItems as $value)
                        <div class="item_group">
                            <p class="title">{{$value->title}}</p>
                            @foreach($value->getChild as $value2)
                                <div class="form-group">
                                    <label class="">{{$value2->title}} : </label>
                                    @if(sizeof($value2->getValue)>0)
                                        <input type="text" class="form-control" name="item_value[{{$value2->id}}][]"
                                               value="{{$value2->getValue[0]->item_value}}">

                                    @else
                                        <input type="text" class="form-control" name="item_value[{{$value2->id}}][]">
                                    @endif
                                    <span class="fa fa-plus-circle"
                                          onclick="add_item_input_value({{$value2->id}})"></span>
                                    <div class="input_item_box" id="input_item_box_{{$value2->id}}">
                                        @if(sizeof($value2->getValue)>1)
                                            @foreach($value2->getValue as $key=>$value3)
                                                @if($key>0)
                                                    <div class="form-group">
                                                        <label></label>
                                                        <input type="text" class="form-control"
                                                               name="item_value[{{$value2->id}}][]"
                                                               value="{{$value3->item_value}}">
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif

                                    </div>
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
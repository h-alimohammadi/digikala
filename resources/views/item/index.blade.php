@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت ویژگی ها','url'=>url('admin/category/'.$category->id.'/items')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت ویژگی های دسته ( {{ $category->name }} )
        </div>
        <div class="panel_content">
            @include('Include.alert')

            <form method="post" action="{{url('admin/category/'.$category->id.'/items')}}">
                @csrf
                <div id="item_box" class="category_items">

                    @foreach($items as $item)
                        <div class="form-group item_group" id="item_{{$item->id}}">
                            <input class="form-control item_input" type="text" name="item[{{$item->id}}]"
                                   placeholder="نام گروه ویژگی" value="{{$item->title}}">
                            <span class="fa fa-plus-circle" onclick="add_child_item({{$item->id}})"></span>

                            <span class="item_remove_message"
                                  onclick="del_row('{{url('admin/category/items/'.$item->id)}}','{{Session::token()}}','آیا از حذف ایتم ها مطمئن هستید؟')"> حذف کلی آیتم های گروه {{ $item->title }}</span>
                            <div class="child_item_box">
                                @php
                                    $i=1;
                                @endphp
                                @foreach($item->getChild as $itemChild)
                                    <div class="form-group child_{{$item->id}}">
                                        {{$i}}-<input type="checkbox"
                                                      name="check_box_input[{{$item->id}}][{{$itemChild->id}}]"
                                                      @if($itemChild->show_item == 1) checked="checked" @endif>
                                        <input type="text" name="child_item[{{$item->id}}][{{$itemChild->id}}]"
                                               class="form-control chile_input_item" placeholder="نام ویژگی ..."
                                               value="{{$itemChild->title}}">
                                        <span class="child_item_remove_message"
                                              onclick="del_row('{{url('admin/category/items/'.$itemChild->id)}}','{{Session::token()}}','آیا از حذف این ایتم مطمئن هستید؟')">حذف ویژگی</span>

                                    </div>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <span class="fa fa-plus-square" onclick="add_item_input()"></span>
                <div class="form-group">
                    <button class="btn btn-primary">ثبت اطلاعات</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function (){
        $('.category_items').sortable();
        $('.child_item_box').sortable();
    });
</script>
@endsection
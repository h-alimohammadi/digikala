@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت فیلتر ها','url'=>url('admin/category/'.$category->id.'/filter')],
    ]])
    <div class="panel">
        <div class="header">
            مدیریت فیلتر های دسته ( {{ $category->name }} )
        </div>
        <div class="panel_content">
            @include('Include.alert')

            <form method="post" action="{{url('admin/category/'.$category->id.'/filter')}}">
                @csrf
                <div id="filter_box" class="category_filters">
                    @php
                        $i=1;
                    @endphp
                    @if(sizeof($filters)>0)
                        @foreach($filters as $filter)
                            <div class="form-group item_group" id="filter_{{$filter->id}}">
                                <select name="item_id[{{$filter->id}}]" class="selectpicker" data-live-search="true">
                                    <option value="0">انتخاب ویژگی (در صورت نیاز)</option>
                                    @foreach($items as $item_key=>$item_value)
                                        @foreach($item_value->getChild as $item_key2=>$item_value2)
                                            <option @if($filter->item_id == $item_value2->id) selected="selected"  @endif value="{{$item_value2->id}}">{{$item_value2->title}}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                                <input style="margin-right: 90px" class="form-control filter_input" type="text" value="{{$filter->title}}"
                                       name="filter[{{$filter->id}}]" placeholder="نام گروه فیلتر">

                                <span class="item_remove_message"
                                      onclick="del_row('{{url('admin/category/filter/'.$filter->id)}}','{{Session::token()}}','آیا از حذف ایتم ها مطمئن هستید؟')"> حذف کلی فیلتر های گروه {{ $filter->title }}</span>
                                <span class="fa fa-plus-circle" onclick="add_child_filter({{$filter->id}})"></span>
                                <div class="child_filter_box">
                                    @if(sizeof($filter->getChild)>0)
                                        @foreach($filter->getChild as $value)
                                            <div class="form-group child_{{$filter->id}}">
                                                {{ $i }}- <input type="text" value="{{$value->title}}"
                                                                 name="child_filter[{{$filter->id}}][{{$value->id}}]"
                                                                 class="form-control child_input_filter"
                                                                 placeholder="نام فیلتر ...">
                                                <span class="child_item_remove_message"
                                                      onclick="del_row('{{url('admin/category/filter/'.$value->id)}}','{{Session::token()}}','آیا از حذف این فیلتر مطمئن هستید؟')">حذف ویژگی</span>

                                            </div>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="form-group item_group" id="filter_-1">
                            <input class="form-control filter_input" type="text" name="filter[-1]"
                                   placeholder="نام گروه فیلتر">
                            <span class="fa fa-plus-circle" onclick="add_child_filter(-1)"></span>
                            <div class="child_filter_box">

                            </div>
                        </div>
                    @endif

                </div>
                <span class="fa fa-plus-square" onclick="add_filter_input()"></span>
                <div class="form-group">
                    <button class="btn btn-primary">ثبت اطلاعات</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.category_filters').sortable();
            $('.child_filter_box').sortable();
        });
    </script>
@endsection
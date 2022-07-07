<div class="container-fluid index_cat_list">
    <ul class="">
        <li class="cat_hover">
            <div></div>
        </li>
        @foreach($catList as $key=>$value)
            <li class="cat_item">
                <a href="{{url('main/'.$value->url)}}">{{$value->name}}</a>
                @if(sizeof($value->getChild)>0)
                    <div class="li_div" {{--@if($key==0) style="display: block" @endif--}}>
                        @php
                            $c=0;
                        @endphp
                        @if(sizeof($value->getChild)>0) @if($c==0)<ul class="list-inline sub_list"> @endif  @endif
                            @foreach($value->getChild as $key2=>$value2)
                                @if($value2->notShow == 0)
                                    @if(get_show_category_count($value2->getChild)>=(14-$c)) @php $c=0; @endphp </ul><ul class="list-inline sub_list"> @endif
                            <li>
                                <a href="{{get_cat_url($value2)}}" class="child_cat">
                                    <span>{{$value2->name}}</span>
                                    <span class="fa fa-angle-left"></span>
                                </a>
                                <ul>
                                    @foreach($value2->getChild as $key3=>$value3)
                                        @if($value3->notShow == 0)
                                            <li>
                                                <a href="{{get_cat_url($value3)}}" class="">{{$value3->name}}</a>
                                            </li>
                                            @php $c++; @endphp
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            @php $c++; @endphp
                            @if($c == 12) </ul>  @php $c=0; @endphp <ul class="list-inline sub_list">  @endif
                            @else
                                @foreach($value2->getChild as $key3=>$value3)
                                    @if(get_show_category_count($value3->getChild)>=(14-$c)) </ul> @php $c=0; @endphp <ul class="list-inline sub_list"> @endif
                            @if($value3->notShow == 0)
                                <li>
                                    <a href="{{get_cat_url($value3)}}" class="child_cat">
                                        <span>{{$value3->name}}</span>
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                    <ul>
                                        @foreach($value3->getChild as $key4=>$value4)
                                            @if($value4->notShow == 0)
                                                <li>
                                                    <a href="{{get_cat_url($value4)}}" class="child_cat">{{$value4->name}}</a>
                                                </li>
                                                @php
                                                    $c++;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                                @php
                                    $c++;
                                @endphp
                            @endif
                            @if($c == 12) </ul>  @php $c=0; @endphp <ul class="list-inline sub_list">  @endif

                            @endforeach
                            @endif
                            @endforeach
                            @if($c!=0) </ul> @endif
                        <div class="show-total-sub-cat">
                            <a href="">
                                <span>مشاهده تمام دسته های </span>
                                <span>{{ $value->name }}</span>
                            </a>
                        </div>
                        @if(!empty($value->img))
                            <a href="" class="sub-menu-pic">
                                <img src="{{asset('files/uploads/category/'.$value->img)}}">
                            </a>
                        @endif
                    </div>
                @endif

            </li>
        @endforeach
        <li class="cat_item left_item">
            <a href="">فروش ویژه</a>
        </li>
    </ul>
</div>
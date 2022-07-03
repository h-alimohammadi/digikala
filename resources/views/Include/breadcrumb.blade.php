<?php

use App\Lib\Jdf;

$jdf = new Jdf();
?>
<div class="breadcrumb">

    <ul class="list-inline">
        <li class="ml-2">
            <a href="{{url('admin')}}">
                <span class="fa fa-home"></span>
                <span class="ml-2">پیشخوان</span>
                @if(isset($data))
                    <span class="fa fa-angle-left"></span>
                @endif
            </a>
        </li>
        @if(isset($data) && is_array($data))
            @foreach($data as $key => $value)
                <li class="ml-2">
                    <a href="{{$value['url']}}">
                        <span class="ml-2">{{$value['title']}}</span>
                        @if($key != sizeof($data)-1 || isset($_GET['trashed']))
                            <span class="fa fa-angle-left"></span>
                        @endif
                    </a>
                </li>
            @endforeach
        @endif
        @if(isset($_GET['trashed']))
            <li class="ml-2">
                <a>
                    <span>سطل زباله</span>
                </a>
            </li>
        @endif
        <li class="date_li">
            <span class="fa fa-calendar"></span>
            <span>امروز</span>
            <span>{{$jdf->jdate('l')}}</span>
            <span>{{$jdf->jdate('j')}}</span>
            <span>{{$jdf->jdate('F')}}</span>
            <span>{{$jdf->jdate('Y')}}</span>
        </li>
    </ul>
</div>

{{--<div class="breadcrumb">--}}

{{--    <ul class="list-inline">--}}
{{--        <li>--}}
{{--            <a href="{{url('admin')}}">--}}
{{--                <span class="fa fa-home"></span>--}}
{{--                <span>پیشخوان</span>--}}
{{--                @if(isset($data))--}}
{{--                    <span class="fa fa-angle-left"></span>--}}
{{--                    @endif--}}
{{--            </a>--}}
{{--        </li>--}}
{{--    </ul>--}}
{{--</div>--}}
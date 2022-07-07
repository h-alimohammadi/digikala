@extends('layouts.Shop')
@section('content')
    <div class="row slider">
        <div class="col-2"></div>
        <div class="col-10">
            @if(sizeof($sliders)>0)
                <div class="slider_box">
                    <div class="position-relative">
                        @foreach($sliders as $key=>$slider)
                            <div class="slide_div an" id="slider_img_{{$key}}" @if($key==0) style="display: block;" @endif  >
                                <a href="{{$slider->url}}" style='background-image: url("{{asset('files/uploads/slider/'.$slider->image_url)}}")'></a>
                            </div>
                        @endforeach
                    </div>
                    <div class="right_slide" onclick="previous()"></div>
                    <div class="left_slide" onclick="next()"></div>
                    <div class="slider_box_footer position-absolute">
                        <div class="slider_bullet_div">
                            @foreach($sliders as $key=>$slider)
                                <span @if($key==0) class="active" @endif id="slide_bullet_{{$key}}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
@section('script')
    <script>
        load_slider('{{sizeof($sliders)}}');
    </script>
@endsection

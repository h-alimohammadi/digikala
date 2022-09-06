@extends('layouts.Shop')

@section('content')
    <div class="content">
        <p class="pt-2">لیست مقایسه ( {{$category->name}} )</p>
        <div class="compare_item_list">
           <div class="content">
              <div class="compare_box">
                  <div class="compare_product_gallery">
                      @foreach($products as $key=>$value)
                          <div class="gallery_box">
                              <div class="remove_product_of_compare_list" data-id="{{$value->id}}">
                                  <span class="fa fa-close"></span>
                              </div>
                              <div class="swiper-container" dir="rtl">
                                  <div class="swiper-wrapper">
                                      @foreach($value->ProductGallery as $key2=>$value2)
                                          <div class="swiper-slide @if($key==0) active  @endif swiper-slide-amazing" data-id="{{$value2->id}}">
                                              <img src="{{asset('files/uploads/products/gallerys/'.$value2->image_url)}}" class="compare_gallery_pic">
                                          </div>
                                      @endforeach
                                  </div>
                                  <div class="swiper-button-next" id="next"></div>
                                  <div class="swiper-button-prev" id="prev"></div>
                              </div>
                              <div class="title">
                                  <a href="{{ url('product/dkp-'.$value->id.'/'.$value->product_url) }}">{{$value->title}}</a>
                              </div>
                              <p class="price">{{replace_number(number_format($value->price))}} تومان</p>
                              <a class="btn btn-primary" href="{{ url('product/dkp-'.$value->id.'/'.$value->product_url) }}">مشاهده و خرید محصول</a>
                          </div>
                      @endforeach
                      @for($i=sizeof($products);$i<4;$i++)
                          <div class="compare_add" data-toggle="modal" data-target=".product_list">
                              <button class="add">
                                  <p class="fa fa-plus-circle"></p>
                                  <p>برای افزودن کالا به لیست مقایسه کلیک کنید</p>
                              </button>
                              <button class="btn btn-dark">افزودن کالا به لیست مقایسه</button>
                          </div>
                      @endfor
                  </div>
                  @foreach($items as $key=>$value)
                      <h5 class="compare_title">{{$value->title}}</h5>
                      <ul class="compare_ul">
                          @foreach($value->getChild as $key2=>$value2)
                              <li class="title">{{$value2->title}}</li>
                              <li class="value">
                                  <div @if(sizeof($products)>0) class="left_border" @endif>
                                      {!! strip_tags(get_item_value(0,$products,$value2->id),'<br>') !!}
                                  </div>
                                  <div @if(sizeof($products)>1) class="left_border" @endif>
                                      {!! strip_tags(get_item_value(1,$products,$value2->id),'<br>') !!}
                                  </div>
                                  <div @if(sizeof($products)>1) class="left_border" @endif>
                                      {!! strip_tags(get_item_value(2,$products,$value2->id),'<br>') !!}
                                  </div>
                                  <div @if(sizeof($products)>1) class="left_border" @endif>
                                      {!! strip_tags(get_item_value(3,$products,$value2->id),'<br>') !!}
                                  </div>
                              </li>
                          @endforeach
                      </ul>
                  @endforeach
              </div>
           </div>
            <compare-product-list :cat_id="{{$products[0]->cat_id}}"></compare-product-list>
        </div>
    </div>
@endsection
@section('link')
    <link href="{{ asset('css/swiper-bundle.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{asset('js/swiper.min.js')}}" type="text/javascript"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
{{--        @php--}}
{{--            if (sizeof($productWarranty) <6){--}}
{{--        @endphp--}}
{{--        $("#next").hide();--}}
{{--        $("#prev").hide();--}}
{{--        @php--}}
{{--            }--}}
{{--        @endphp--}}
    </script>

@endsection
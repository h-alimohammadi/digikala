@if(sizeof($productList)>0)
    <div class="product_box ">
        <div class="box_title">
            <h6>{{ $title }}</h6>
        </div>
        <div class="swiper-container products">
            <div class="swiper-wrapper">
                @foreach($productList as $product)
                    @php
                        $price1 = $product->price+$product->discount_price;
                    @endphp
                    <div class="swiper-slide product">
                        <a href="{{url('product/dkp-'.$product->id.'/'.$product->product_url)}}">
                            <div class="position-relative">
                                @if(!empty($product->discount_price))
                                    <span class="discount-badge">
                                        @php
                                            $d= ($product->price/$price1)*100;
                                            $d =100-$d;
                                            $d = round($d);
                                        @endphp
                                        ٪{{replace_number($d)}}
                                    </span>
                                @endif
                                <img src="{{url('files/uploads/thumbnails/'.$product->image_url)}}">

                            </div>
                            <p class="title">
                                @if(strlen($product->title)>50)
                                    {{ mb_substr($product->title,0,33).'...' }}
                                @else
                                    {{ $product->title }}
                                @endif
                            </p>
                            <div class="price">
                                <div>{{ replace_number(number_format($product->price)) }} تومان</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
{{--@section('script')--}}
{{--    <script type="text/javascript" src="{{ asset('js/swiper.min.js') }}"></script>--}}
{{--    <script>--}}
{{--        var sliders=new Swiper('.product_box .swiper-container',{--}}
{{--            // pagination:{--}}
{{--            //     el:'.swiper-pagination'--}}
{{--            // }--}}
{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}


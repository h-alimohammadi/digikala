@if(sizeof($productList)>0)
    <div class="product_box ">
        <div class="box_title">
            <h6>{{ $title }}</h6>
        </div>
        <div class="product_list" dir="rtl">
            @foreach($productList as $product)
                @php
                    $price1 = $product->price+$product->discount_price;
                @endphp
                <a href="{{url('product/dkp-'.$product->id.'/'.$product->product_url)}}">
                    <div class="product">
                        <div class="position-relative">
                            <img src="{{url('files/uploads/thumbnails/'.$product->image_url)}}">
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
                        </div>
                        <p class="title">
                            @if(strlen($product->title)>50)
                                {{ mb_substr($product->title,0,33).'...' }}
                            @else
                                {{ $product->title }}
                            @endif
                        </p>
                        <div class="discount_price">
                            @if(!empty($product->discount_price))
                                <del>{{ replace_number(number_format($price1)) }}</del>
                            @endif
                        </div>
                        <div class="price">
                            <div>{{ replace_number(number_format($product->price)) }} تومان</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif


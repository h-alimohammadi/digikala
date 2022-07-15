 @if(sizeof($productWarranty)>0)
<div class="row incredible-offers">
    <div class="col-3">
        <div style="margin-top: 20px">
            <a href="{{url('')}}">
                <img src="{{url('files/images/1000007524.jpg')}}" class="index_pic">
            </a>
            <a href="{{url('')}}">
                <img src="{{url('files/images/1000007568.jpg')}}" class="index_pic">
            </a>
        </div>
    </div>
    <div class="col-9">
        <div class="discount_box">
            <div class="row">
                <div class="discount_box_content w-100">
                    @foreach($productWarranty as $key=>$value)
                        <div class="item an" @if($key == 0) style="display: block;"
                             @endif id="discount_box_link_{{$value->id}}">
                            <div class="row">
                                <div class="col-6">
                                    <div class="discount_button_bar"></div>
                                    <a href="{{url('product/dkp-'.$value->product->id.'/'.$value->product->product_url)}}">
                                        <img src="{{url('files/uploads/thumbnails/'.$value->product->image_url)}}">
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{url('product/dkp-'.$value->product->id.'/'.$value->product->product_url)}}">
                                        <div>
                                            <div class="price_box">
                                                <del>{{replace_number(number_format($value->price1))}}
                                                    تومان
                                                </del>
                                                <div class="incredible-offers-price">
                                                    <label>{{replace_number(number_format($value->price2))}}
                                                        تومان</label>
                                                    <span class="discount-badge">
                                                            @php
                                                                $a= ($value->price1 / $value->price2)*100;
                                                                $a=round($a-100);
                                                            @endphp
                                                             ٪{{replace_number($a) .' تخفیف'}}
                                                        </span>
                                                </div>
                                                <div class="discount_title">{{$value->product->title}}</div>
                                                <ul class="important_item_ul">
                                                    @foreach($value->itemValue as $item)
                                                        <li>
                                                            {{$item->importantItem->title}} :
                                                            {{$item->item_value}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @if($value->product_number>0)
                                                    <counter
                                                            second="{{$value->offers_last_time-time() }}"></counter>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="discount_left_item">
                    @php $i=0; @endphp
                    @foreach($productWarranty as $key=>$value)
                        <div id="item_number_{{$i}}" class="@if($i==0) active  @endif"
                             data-id="{{$value->id}}">
                            {{ $value->product->category->name }}
                            @php $i++; @endphp
                        </div>
                    @endforeach

                    @if(sizeof($productWarranty) >= 9)
                        <a href="{{url('incredible-offers')}}">
                            مشاهده همه شگفت انگیزها
                        </a>
                    @endif
                </div>
            </div>
            <div class="discount_box_footer">
                <div class="swiper-container" dir="rtl">
                    <div class="swiper-wrapper">
                        @foreach($productWarranty as $key=>$value)
                            <div class="swiper-slide @if($key==0) active  @endif swiper-slide-amazing"
                                 data-id="{{$value->id}}">
                                {{ $value->product->category->name }}
                            </div>
                        @endforeach
                        @if(sizeof($productWarranty)>0)
                            <div class="swiper-slide"></div>
                        @endif
                    </div>
                    <div class="slick-next" id="next"></div>
                    <div class="slick-prev" id="prev"></div>
                </div>
            </div>

        </div>
    </div>
</div>
@endif
<form method="post" action="{{url('Cart')}}" id="add_cart_form">
    @csrf
    @php
        $warranty_id=0;
        $product_price1=0;
        $product_price2=0;
        $time_offer=0;
    @endphp
    <ul class="color_ul">
        @foreach($product->getColor as $value)
            @if(check_has_color_in_product_warranty_list($product->productWarranties,$value->color_id))
                @if($color_id ==0 ) <?php if(get_first_color_id($product->productWarranties,$value->color_id)){$color_id= $value->color_id;} ?> @endif
                <li class="color_li @if($color_id == $value->color_id) active @endif" data="{{ $value->color->id }}">
                    <label>
                        <span class="ui_variant_shape" style="background: {{$value->color->code}}"></span>
                        <span class="color_name">{{$value->color->name}}</span>
                    </label>
                </li>
            @endif
        @endforeach
    </ul>
    <input type="hidden" name="color_id" value="{{ $color_id }}">
    <p class="info_item_product">
        <span class="fa fa-check-square"></span>
        @foreach($product->productWarranties as $key=>$value)
            @if($color_id>0)
                @if($value->color_id==$color_id && $warranty_id==0)
                    @php
                        $warranty_id=$value->warranty_id;
                        $send_time=$value->send_time;
                        $product_price1=$value->price1;
                        $product_price2=$value->price2;
                    @endphp
                    {{ $value->warranty->name }}
                    @php
                        $time_offer = $value->offers_last_time - time() > 0 ? $value->offers_last_time - time(): 0;
                    @endphp
                @endif
            @else
                @if($key == 0)
                    @php
                        $warranty_id = $value->warranty_id;
                        $send_time = $value->send_time;
                        $product_price1 = $value->price1;
                        $product_price2 = $value->price2;
                    @endphp
                    {{$value->warranty->name}}
                    @if($value->offers==1)
                        @php
                            $time_offer = $value->offers_last_time - time() > 0 ? $value->offers_last_time - time(): 0;
                        @endphp
                    @endif
                @endif
            @endif
        @endforeach
    </p>
    <span id="offer_time" data="{{ $time_offer  }}"></span>

    <p class="info_item_product">
        <span class="fa fa-ambulance"></span>

        @if(isset($send_time))
            @if($send_time != 0)
                <span>ارسال از {{replace_number($send_time)}} روز کاری آینده</span>
                <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="این کالا در انبار فروشنده موجود است. برای ارسال باید برای مدت زمان ذکر شده منتظر بمانید."></span>

            @else
                <span>آماده ارسال</span>
                <span class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="این کالا در حال حاضر در انبار دیجی آنلاین موجود، آماده پردازش و ارسال است."></span>
            @endif
        @endif
    </p>

    <input type="hidden"name="warranty_id" id="warranty_id" value="{{$warranty_id}}">
    <input type="hidden"name="product_id" id="product_id" value="{{$product->id}}">
    <div class="d-inline-flex w-100">
        <div>
            @if($product_price2 != $product_price1)
                <del>{{ replace_number(number_format($product_price1)) }}</del>
            @endif
            <span class="final_product_price">{{ replace_number(number_format($product_price2)) }} تومان </span>
        </div>
        @if($product_price2 != $product_price1)
            <div class="product_discount" data-title="تخفیف">
                @php
                    $a= ($product_price2 / $product_price1)*100;
                    $a=round($a-100);
                @endphp
                ٪{{replace_number(abs($a))}}
            </div>
        @endif
    </div>

</form>

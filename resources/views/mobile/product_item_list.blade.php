<div class="mobile_data_box_item">
    <div class="header">
        <span>مشخصات فنی</span>
        <a id="hide_item_product" role="button">
            <span>بازگشت</span>
            <span class="fa fa-angle-left"></span>
        </a>
    </div>
    <div class="content">
        @if(sizeof($productItems) && $productItemCount>0)
            <table class="item_table" style="">
                @foreach($productItems as $key=>$value)
                    <tr>
                        <td colspan="2" style="padding: 15px 0">
                            <span class="item_name">{{ $value->title }}</span>
                        </td>
                    </tr>
                    @foreach($value->getChild as $key2=>$value2)
                        @if(sizeof($value2->getValue)>0 )
                            <tr>
                                <td class="product_item_name">
                                    {{$value2->title}}
                                </td>
                            </tr>
                            <tr>
                                <td class="product_item_value">
                                    {{$value2->getValue[0]->item_value}}
                                </td>
                            </tr>
                            @foreach($value2->getValue as $key3=>$value3)
                                @if($key3>0 )
                                    <tr>
                                        <td class="product_item_value">
                                            {{$value3->item_value}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            </table>
        @else
            <p class="empty_message">
                مشخصات فنی برای این محصول ثبت نشده است.
            </p>
        @endif
    </div>
</div>
@if($productItemCount>0)
    @if(!isset($remove_title))
        <h6 style="margin-right: -15px">ویژگی های محصول</h6>
    @endif
    <ul class="important_item_ul">
        @php($i =0 )
        @foreach($productItems as $key1=>$value1)
            @foreach($value1->getChild as $key2=>$value2)
                @if($value2->show_item == 1)
                    <li @if($i>1)class="more_important_item"@endif>
                        <span>{{ $value2->title .' : ' }}</span>
                        <span>
                        @foreach($value2->getValue as $key2=>$value2)
                                @if(strlen($value2->item_value)>30)
                                    <br>
                                @endif
                                {{ $value2->item_value }}
                            @endforeach
                    </span>
                    </li>
                    @php($i++)
                @endif
            @endforeach
        @endforeach
    </ul>
    @if(!isset($remove_title))
        @if($i>1)
            <p class="show_more_important_item">موارد بیشتر</p>
        @endif
    @endif
@endif
@php
    $moarefi=true;
    $moarefi2=true;
@endphp
<div class="review_box">
    <h2 class="headline">
        @if(!empty($product->ename) && $product->ename!= null)
            <span>{{ $product->ename }}</span>
        @endif

    </h2>
    @if(!empty($product->tozihat))
        @php
            $moarefi=false;
        @endphp
        <div class="tozihat">
            <div>
              <div class="content">
                  <div id="product_tozihat">
                      {!!  $product->tozihat !!}
                  </div>
                  <a class="more_content">
                      <span>ادامه مطلب</span>
                  </a>
              </div>
            </div>
        </div>
    @endif

    @foreach($review as $key=>$value)
        @if(empty($value->title))
            @php
                $moarefi2=false;
            @endphp
            <div class="item_row">
                <button class="export_button @if($moarefi && $key==0) plus_icon @endif"></button>
                <h3>معرفی</h3>
                <div class="content"@if($moarefi && $key==0) style="display: block" @endif>
                    @php
                        $tozihat=$value->tozihat;
                        $find='style="width:100%"';
                        $replace='class="review_image"';
                        $tozihat=str_replace($find,$replace,$tozihat);

                    @endphp
                    {!!  $tozihat !!}
                </div>
            </div>
        @endif
    @endforeach
    @foreach($review as $key=>$value)
        @if(!empty($value->title))
            <div class="item_row">
                <button class="export_button  @if($moarefi2 && $key==0) plus_icon @endif"></button>
                <h3>{{ $value->title }}</h3>
                <div class="content" @if($moarefi2 && $key==0) style="display: block" @endif>
                    @php
                        $tozihat=$value->tozihat;
                        $find='style="width:100%"';
                        $replace='class="review_image"';
                        $tozihat=str_replace($find,$replace,$tozihat);

                    @endphp
                    {!!  $tozihat !!}
                </div>
            </div>
        @endif
    @endforeach
</div>
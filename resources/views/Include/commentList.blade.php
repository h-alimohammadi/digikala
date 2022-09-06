<form method="post" id="data_form">
    @csrf
    @php $jdf= new \App\Lib\Jdf(); $typeScore=\App\CommentScore::getScorTypeLabel(); @endphp
    @foreach($comments as $comment)
        <div class="comment_box @if($comment->status == 1) Accepted @else pending_approval @endif">
            <div class="comment_header_box">
                <div>
                    @if(!isset($remove_delet_link))
                        <input type="checkbox" name="id_comments[]" class="check_box" value="{{$comment->id}}">
                    @endif
                    <span class="comment_status" comment-id="{{{$comment->id}}}" comment-status="{{ $comment->status }}">
                        @if($comment->status == 1)
                            تایید شده
                        @else
                            در انتظار تایید
                        @endif
                    </span>
                </div>
                <div>
                    <span>ثبت شده توسط </span>
                    @if($comment->getUserInfo)
                        {{ $comment->getUserInfo->first_name . $comment->getUserInfo->last_name }}
                    @else
                        <span>ناشناس</span>
                    @endif
                    <span>در تاریخ</span>
                    <span>{{ $jdf->jdate('d F Y',$comment->time) }}</span>
                </div>
                <div>
                    @if(!isset($remove_delet_link))
                        @if(!$comment->trashed())
                            <span data-toggle="tooltip" data-placement="bottom" title="حذف نظر"
                                  class="fa fa-trash"
                                  onclick="del_row('{{url('admin/comments/'.$comment->id)}}','{{Session::token()}}','آیا از حذف این نظر مطمئن هستید ؟')"></span>
                        @else
                            <span data-toggle="tooltip" data-placement="bottom" title="حذف نظر برای همیشه"
                                  class="fa fa-trash"
                                  onclick="del_row('{{url('admin/comments/'.$comment->id)}}','{{Session::token()}}','آیا از حذف این نظر مطمئن هستید ؟')"></span>
                        @endif
                        @if($comment->trashed())
                            <span data-toggle="tooltip" data-placement="bottom" title="بازیابی نظر"
                                  class="fa fa-refresh"
                                  onclick="restore_row('{{url('admin/comments/'.$comment->id)}}','{{Session::token()}}','آیا از بازیابی این نظر مطمئن هستید ؟')"></span>
                        @endif
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <ul class="rating_ul">
                        @foreach(getScoreItem($comment->getScore,$typeScore) as $item)
                            <li>
                                <label>{{ $item['label'] }}</label>
                                <div class="rating" data-rate-digit="{{ $item['type'] }}">
                                    <div class="rating_value" style="width: {{ $item['value'] * 25 }}%;"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if($comment->order_id > 0 )
                        <div class="message_purchased">
                            <a target="_blank" href="{{ url('admin/orders/'.$comment->order_id) }}">
                                <span class="fa fa-shopping-cart"></span>
                                خریدار محصول
                            </a>
                        </div>
                    @endif
                    <span>ثبت شده در محصول</span>
                    <p>{{ $comment->getProduct->title }}</p>
                </div>
                <div class="col-md-6">
                    {{$comment->title}}
                    <div class="row">
                        <div class="col-md-6">
                            @php $advantages=$comment->advantage @endphp
                            @if(sizeof($advantages)>1)
                                <span class="evaluation_label">نقاط قوت</span>
                                <ul class="evaluation_ul advantage">
                                    @foreach($advantages as $key=>$advantage)
                                        @if(!empty($advantage))
                                            <li><span>{{$advantage}}</span></li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @php $disadvantages=$comment->disadvantage @endphp
                            @if(sizeof($disadvantages)>1)
                                <span class="evaluation_label">نقاط ضعف</span>
                                <ul class="evaluation_ul disadvantage">
                                    @foreach($disadvantages as $key=>$disadvantage)
                                        @if(!empty($disadvantage))
                                            <li><span>{{$disadvantage}}</span></li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="comment_content">{{$comment->content}}</div>
                </div>
            </div>
        </div>
    @endforeach

    @if(sizeof($comments) == 0)
        <p class="pt-3 pb-3 text-center">رکوردی برای نمایش یافت نشد.</p>
        @endif
</form>

<div class="dropdown">
    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        گزینه ها
    </button>
    @php
    $create_param='';
    $trashed_param='';
    if (isset($queryString) && is_array($queryString)){
    $create_param = '?'.$queryString['param'].'='.$queryString['value'];
    $trashed_param = '&'.$queryString['param'].'='.$queryString['value'];
    }
    @endphp
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{url($route.'/create'.$create_param)}}">
            <span class="fa fa-pencil"></span>
            <span>افزودن {{$title}} جدید</span>
        </a>
        <a class="dropdown-item" href="{{url($route.'?trashed=true'.$trashed_param)}}">
            <span class="fa fa-trash"></span>
            <span>سطل زباله ({{replace_number($count)}})</span>
        </a>
        <a class="dropdown-item off item_form" id="remove_items" msg="آیا از حذف {{ $title }} های انتخابی مطمئن هستید؟">
            <span class="fa fa-remove"></span>
            <span>حذف {{$title}} ها</span>
        </a>
        @if(isset($_GET['trashed']) && $_GET['trashed'] == 'true')
            <a class="dropdown-item off item_form" id="restore_items" msg="آیا از بازیابی {{ $title }} های انتخابی مطمئن هستید؟">
                <span class="fa fa-remove"></span>
                <span>بازیابی  {{$title}} ها </span>
            </a>
        @endif
    </div>
</div>
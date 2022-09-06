@extends('layouts.admin')
@section('content')
    @include('Include.breadcrumb',['data'=>[
    ['title'=>'مدیریت نظرات','url'=>url('admin/comments')],
    ]])
    <div class="panel">
        <div class="header">
            نظرات کاربران

            @include('Include.item_table',['count'=>$trashed_comment_count,'route'=>'admin/comments','title'=>'نظر','remove_new_record'=>true])
        </div>
        <div class="panel_content">
            @include('Include.alert')
            @include('Include.commentList')
            {{ $comments->links() }}

        </div>
    </div>
@endsection

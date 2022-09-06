<?php

namespace App\Http\Controllers\Admin;


use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends CustomController
{
    protected $model = 'Comment';
    protected $title = 'نظر';
    protected $route_params = 'comments';

    public function index(Request $request)
    {
        $comments = Comment::getData($request->all());
        $trashed_comment_count = Comment::onlyTrashed()->count();
        return view('comment.index', compact('comments', 'trashed_comment_count', 'request'));
    }

    public function changeStatus(Request $request)
    {
        if ($request->ajax()) {
            $comment_id = $request->get('comment_id');
            $comment = Comment::where('id', $comment_id)->firstOrFail();
            if ($comment) {
                $status = $comment->status == 1 ? 0 : 1;
                $comment->status = $status;
                DB::table('comment_scores')->where('comment_id',$comment->id)->update(['status'=>$status]);
                if ($comment->update()) {
                    return 'Ok';
                } else {
                    return 'error';
                }
            } else {
                return 'error';
            }
        }
    }


}

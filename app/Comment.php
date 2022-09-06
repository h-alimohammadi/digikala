<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use SoftDeletes;
    protected $table = 'comments';
    protected $fillable = ['user_id', 'product_id', 'title', 'content', 'time', 'order_id', 'like', 'dislike', 'advantage', 'disadvantage', 'status'];

    public static function addComment($request, $product)
    {
        DB::beginTransaction();
        $order_id = get_comment_order_id($product->id, $request->user()->id);
        $comment = new Comment($request->all());
        $comment->user_id = $request->user()->id;
        $comment->product_id = $product->id;
        $comment->time = time();
        $comment->advantage = get_comment_item($request->get('advantage', []));
        $comment->disadvantage = get_comment_item($request->get('disadvantage', []));
        $comment->status = 0;
        $comment->order_id = $order_id;
        try {
            $comment->saveOrFail();

            $score = 0;
            $array_score = $request->get('score_item', []);
            if (sizeof($array_score) == 6) {
                $score = $array_score[0] + $array_score[1] + $array_score[2] + $array_score[3] + $array_score[4] + $array_score[5];

            }
            $product->score += $score;
            $product->score_count = $product->score_count + 1;
            $product->update();

            addScore($array_score, $comment->id, $product->id);
            DB::commit();
            return [
                'status' => 'Ok',
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            return [
                'status' => 'error',
            ];
        }


    }

    public static function getData($request)
    {
        $string = '?';
        $comment = self::with(['getProduct', 'getUserInfo', 'getScore'])->whereHas('getScore')->orderBy('id', 'Desc');
        if (isTrashed($request)) {
            $comment = $comment->onlyTrashed();
            $string = create_paginate_url($string, 'trashed=true');
        }
        if (array_key_exists('user_id', $request) && !empty($request['user_id'])) {
            $comment = $comment->where('user_id', $request['string']);
            $string = create_paginate_url($string, 'string=' . $request['string']);
        }
        $comment = $comment->paginate(10);
        $comment->withPath($string);
        return $comment;
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, 'id', 'product_id')
            ->select(['id', 'title'])->withDefault('محصول حذف شده');
    }

    public function getUserInfo()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id', 'user_id')
            ->select(['first_name', 'last_name']);
    }

    public function getScore()
    {
        return $this->hasOne(CommentScore::class, 'comment_id', 'id');
    }

    public function getAdvantageAttribute($value)
    {
        $e = explode('|[@#]', $value);
        return $e;
    }

    public function getDisadvantageAttribute($value)
    {
        $e = explode('|[@#]', $value);
        return $e;
    }

    public static function getCommentProductList($product_id, $orderBy)
    {
        $array = array();
        $n = CommentScore::where(['product_id' => $product_id, 'status' => 1])->count();
        $sum1 = CommentScore::where('product_id', $product_id)->sum('score1');
        $sum2 = CommentScore::where('product_id', $product_id)->sum('score2');
        $sum3 = CommentScore::where('product_id', $product_id)->sum('score3');
        $sum4 = CommentScore::where('product_id', $product_id)->sum('score4');
        $sum5 = CommentScore::where('product_id', $product_id)->sum('score5');
        $sum6 = CommentScore::where('product_id', $product_id)->sum('score6');
        if ($n > 0) {
            $sum1 = $sum1 / $n;
            $sum2 = $sum2 / $n;
            $sum3 = $sum3 / $n;
            $sum4 = $sum4 / $n;
            $sum5 = $sum5 / $n;
            $sum6 = $sum6 / $n;
        }
        $comments = Comment::with(['getUserInfo', 'getScore'])->whereHas('getScore')
            ->where(['product_id' => $product_id, 'status' => 1]);
        if ($orderBy == 1) {
            $comments = $comments->orderBy('order_id', 'DESC');
        } elseif ($orderBy == 2) {
            $comments = $comments->orderBy('like', 'DESC');
        } elseif ($orderBy == 3) {
            $comments = $comments->orderBy('id', 'DESC');
        }
        $comments = $comments->paginate(10);
        $array['comment'] = $comments;
        $avg = $sum1 + $sum2 + $sum3 + $sum4 + $sum5 + $sum6;
        $avg = $avg / 6;
        $array['avg'] = round($avg);
        $array['comment_count'] = $n;
        $array['avg_score'] = [$sum1, $sum2, $sum3, $sum4, $sum5, $sum6];

        return $array;
    }

    public static function addUserScore($comment_id, $score_type)
    {
        $comment = self::find($comment_id);
        if ($comment) {
            $user_id = auth()->user()->id;
            $user_scored_status = DB::table('user_scored_status')->where(['user_id' => $user_id, 'row_id' => $comment_id, 'score_type' => $score_type, 'type' => 'comment'])->first();
            if ($user_scored_status) {
                DB::table('user_scored_status')->where(['user_id' => $user_id, 'row_id' => $comment_id, 'score_type' => $score_type, 'type' => 'comment'])->delete();
                $comment->$score_type = $comment->$score_type - 1;
                $comment->update();
                return 'remove';

            } else {
                DB::table('user_scored_status')->insert([
                    'user_id' => $user_id,
                    'row_id' => $comment_id,
                    'score_type' => $score_type,
                    'type' => 'comment'
                ]);
                $comment->$score_type = $comment->$score_type + 1;
                $comment->update();
                return 'add';
            }
        } else {
            return 'error';
        }
    }
}


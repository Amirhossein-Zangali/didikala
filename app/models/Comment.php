<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    public $timestamps = false;
    protected $primaryKey = 'id';

    function getComments($limit = 9, $order = 'created_at', $order_type= 'desc')
    {
        return $this->where([['id', '!=', '0'], ['question', 0]])->limit($limit)->orderBy($order, $order_type)->get();
    }

    function getComment($id = 0)
    {
        return $this->where([['id', $id], ['question', 0]])->first();
    }

    function addComment($data){
        $comment = new Comment();
        $comment->user_id = $data[0];
        $comment->product_id = $data[1];
        $comment->strengths = $data[2];
        $comment->weaknesses = $data[3];
        $comment->content = $data[4];
        $comment->recommendation = $data[5];
        $comment->status = 0;
        if($comment->save())
            return $comment->id;
        else
            return false;
    }

    static function deleteComment($id){
        return Comment::where('id', $id)->delete();
    }

    static function getProductComments($product_id){
        return Comment::where([['product_id', $product_id], ['status', 1], ['question', 0]])->get();
    }

    static function getUserComments($user_id){
        return Comment::where('user_id', $user_id)->where('question', 0)->orderBy('created_at', 'desc')->get();
    }

    static function getUserQuestions($user_id){
        return Comment::where('user_id', $user_id)->where('question', 1)->where('reply', 0)->orderBy('created_at', 'desc')->get();
    }

    static function haveReply($comment_id){
        return Comment::where('reply', $comment_id)->exists();
    }

    static function getReply($comment_id){
        return Comment::where('reply', $comment_id)->orderBy('created_at', 'desc')->first();
    }

    static function getProductCommentCount($product_id){
        return Comment::where([['product_id', $product_id], ['status', 1], ['question', 0]])->count();
    }

    function getQuestions($limit = 9, $order = 'created_at', $order_type= 'desc')
    {
        return $this->where([['id', '!=', '0'], ['question', 1]])->limit($limit)->orderBy($order, $order_type)->get();
    }

    function getQuestion($id = 0)
    {
        return $this->where([['id', $id], ['question', 1]])->first();
    }

    function addQuestion($data, $reply = 0, $status = 0){
        $comment = new Comment();
        $comment->user_id = $data[0];
        $comment->product_id = $data[1];
        $comment->content = $data[2];
        $comment->strengths = '';
        $comment->weaknesses = '';
        $comment->question = 1;
        $comment->status = $status;
        $comment->reply = $reply;
        if($comment->save())
            return $comment->id;
        else
            return false;
    }

    static function getProductQuestions($product_id){
        return Comment::where([['product_id', $product_id], ['status', 1], ['question', 1], ['reply', 0]])->get();
    }

    static function getProductQuestionsCount($product_id){
        return Comment::where([['product_id', $product_id], ['status', 1], ['question', 1], ['reply', 0]])->count();
    }

    static function getReplyProductQuestions ($product_id, $reply){
        return Comment::where([['product_id', $product_id], ['status', 1], ['question', 1], ['reply', $reply]])->get();
    }

}
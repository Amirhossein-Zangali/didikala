<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    public $timestamps = false;
    protected $primaryKey = 'id';

    static public $comment_count = 0;
    static public $question_count = 0;
    static public $itemPerPage = ITEM_PER_PAGE;

    static function setCommentsCount()
    {
        Comment::$comment_count = Comment::getAllComments()->count();
    }

    static function setQuestionsCount()
    {
        Comment::$question_count = Comment::getAllQuestions()->count();
    }

    static function getCommentPageCount()
    {
        return ceil(Comment::$comment_count / Comment::$itemPerPage);
    }

    static function getQuestionPageCount()
    {
        return ceil(Comment::$question_count / Comment::$itemPerPage);
    }

    static function convertRecommendation($recommendation)
    {
        if ($recommendation == 0)
            return 'نظری ندارد';
        if ($recommendation == 1)
            return 'پیشنهاد می کند';
        if ($recommendation == -1)
            return 'پیشنهاد نمی کند';
    }

    static function convertStatus($status)
    {
        if ($status == 0)
            return 'تایید نشده';
        if ($status == 1)
            return 'تایید شده';
        if ($status == -1)
            return 'رد شده';
    }

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

    static function getWriterComments($user_id, $limit = 0, $offset = 0){
        $user_products = Product::getUserProducts($user_id);
        $user_products_id = [];
        foreach ($user_products as $user_product) {
            $user_products_id[] = $user_product->id;
        }
        if ($offset > 0)
            return Comment::whereIn('product_id', $user_products_id)->where('question', 0)->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
        if ($limit > 0)
            return Comment::whereIn('product_id', $user_products_id)->where('question', 0)->orderBy('created_at', 'desc')->limit($limit)->get();
        else
            return Comment::whereIn('product_id', $user_products_id)->where('question', 0)->orderBy('created_at', 'desc')->get();

    }

    static function getWriterQuestions($user_id){
        $user_products = Product::getUserProducts($user_id);
        $user_products_id = [];
        foreach ($user_products as $user_product) {
            $user_products_id[] = $user_product->id;
        }
        return Comment::whereIn('product_id', $user_products_id)->where('question', 1)->where('reply', 0)->orderBy('created_at', 'desc')->get();
    }

    static function getAllQuestions($limit = 0, $offset = 0){
        if ($offset > 0)
            return Comment::where('question', 1)->where('reply', 0)->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
        if ($limit > 0)
            return Comment::where('question', 1)->where('reply', 0)->orderBy('created_at', 'desc')->limit($limit)->get();
        else
            return Comment::where('question', 1)->where('reply', 0)->orderBy('created_at', 'desc')->get();
    }

    static function getAllComments($limit = 0, $offset = 0){
        if ($offset > 0)
            return Comment::where('question', 0)->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
        if ($limit > 0)
            return Comment::where('question', 0)->orderBy('created_at', 'desc')->limit($limit)->get();
        else
            return Comment::where('question', 0)->orderBy('created_at', 'desc')->get();
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
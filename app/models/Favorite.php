<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public static function addFavorite($user_id, $product_id)
    {
        $favorite = new Favorite();
        $favorite->user_id = $user_id;
        $favorite->product_id = $product_id;
        if ($favorite->save())
            return $favorite->id;
        else
            return false;
    }
    public static function removeFavorite($user_id, $product_id){
        return Favorite::where([['user_id', $user_id],['product_id', $product_id]])->delete();
    }
}
<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'id';

    function getProducts($limit = 9, $order = 'created_at', $order_type= 'desc')
    {
        return $this->where('id', '!=', '0')->limit($limit)->orderBy($order, $order_type)->get();
    }

    function getProduct($id = 0)
    {
        return $this->where('id', $id)->first();
    }

    function getTopSaleProducts($limit = 9)
    {
        return $this->where('sale_count', '>', '0')->orderBy('sale_count', 'desc')->limit($limit)->get();
    }

    function getTopDiscountProducts($limit = 9)
    {
        return $this->where('discount_percent', '>', '0')->orderBy('discount_percent', 'desc')->limit($limit)->get();
    }

    function getSearchProducts($params, $order = 'created_at', $order_type= 'desc' , $limit = 12)
    {
        $search = ['title', 'LIKE', "%$params[0]%"];
        $category = $params[1] == 0 ? ['category_id', '>', 0] : ['category_id',  $params[1]];
        $price_start = $params[2] != 0 ? ['price', '>=', $params[2]*10] : ['price', '!=', -1];
        $price_end = $params[3] != 0 ? ['price', '<=', $params[3]*10] : ['price', '!=', -1];
        $stock = $params[4] ? ['stock', '>', 0] : ['stock', '>=', 0];
        return $this->where([$search, $category, $price_start, $price_end, $stock])->orderBy($order, $order_type)->limit($limit)->get();
    }

    static function hasDiscount($id)
    {
        return Product::where([['discount_percent', '>', '0'], ['id', $id]])->exists();
    }

    static function getPrice($id)
    {
        return number_format((Product::where('id', $id)->first()->price)/10);
    }

    static function getSalePrice($id){
        $product = Product::where('id', $id)->first();
        return number_format($product->price/10 - ($product->price/10 * ($product->discount_percent / 100)));
    }

    static function getCountCategory($id)
    {
        return Product::where('category_id', $id)->count() + Category::getCountSubCategories($id);
    }

    static function getCategoryProducts($id){
        $category = new Category();
        $category = $category->where('sub_cat', $id)->first();
        return Product::whereIn('category_id', [$id, @$category->id])->get();
    }
}
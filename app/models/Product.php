<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'id';

    function getProducts($limit = 9)
    {
        return $this->where('id', '!=', '0')->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    function getTopSaleProducts($limit = 9)
    {
        return $this->where('sale_count', '>', '0')->orderBy('sale_count', 'desc')->limit($limit)->get();
    }

    function getTopDiscountProducts($limit = 9)
    {
        return $this->where('discount_percent', '>', '0')->orderBy('discount_percent', 'desc')->limit($limit)->get();
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
}
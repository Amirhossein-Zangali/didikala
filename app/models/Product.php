<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'id';

    static public $products_count = 0;
    static public $itemPerPage = ITEM_PER_PAGE;

    static public $offset = 0;

    function getProducts($limit = 8, $order = 'created_at', $order_type = 'desc', $offset = 0)
    {
        $products = $this->where('id', '!=', '0')->orderBy($order, $order_type);
        Product::$products_count = $products->count();
        return $products->limit($limit)->offset($offset)->get();
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

    function getSearchProducts($params, $order = 'created_at', $order_type = 'desc', $offset = 0, $limit = 8)
    {
        $search = ['title', 'LIKE', "%$params[0]%"];
        $category = new Category();
        $categories = $category->where('sub_cat', $params[1])->get();
        $category_ids = [$params[1]];
        foreach ($categories as $category) {
            $category_ids[] = $category->id;
        }
        $category = [array_values($category_ids)][0];
        $price_start = $params[2] != 0 ? ['price', '>=', $params[2] * 10] : ['price', '!=', -1];
        $price_end = $params[3] != 0 ? ['price', '<=', $params[3] * 10] : ['price', '!=', -1];
        $stock = $params[4] ? ['stock', '>', 0] : ['stock', '>=', 0];
        $products = $this->where([$search, $price_start, $price_end, $stock])->whereIn('category_id', $category)->orderBy($order, $order_type);
        Product::$products_count = $products->count();
        return $products->limit($limit)->offset($offset)->get();
    }

    static function hasDiscount($id)
    {
        return Product::where([['discount_percent', '>', '0'], ['id', $id]])->exists();
    }

    static function getPrice($id, $format = true)
    {
        if ($format)
            return number_format((Product::where('id', $id)->first()->price) / 10);
        else
            return (Product::where('id', $id)->first()->price) / 10;
    }

    static function getSalePrice($id, $format = true)
    {
        $product = Product::where('id', $id)->first();
        if ($format)
            return number_format($product->price / 10 - ($product->price / 10 * ($product->discount_percent / 100)));
        else
            return $product->price / 10 - ($product->price / 10 * ($product->discount_percent / 100));
    }

    static function getProfit($productId, $format = true) {
        if ($format)
            return number_format(Product::getPrice($productId, false) - Product::getSalePrice($productId, false));
        else
            return Product::getPrice($productId, false) - Product::getSalePrice($productId, false);
    }

    public static function getProfitPercent($productId, $format = true) {
        $profit = Product::getProfit($productId, false);
        $total = Product::getSalePrice($productId, false);
        $subTotal = Product::getPrice($productId, false);
        $average = ($total + $subTotal) / 2;

        $percentProfit = ($profit / $average) * 100;
        if ($format)
            return number_format($percentProfit);
        else
            return $percentProfit;
    }

    static function getCountCategory($id)
    {
        return Product::where('category_id', $id)->count() + Category::getCountSubCategories($id);
    }

    static function getCategoryProducts($id, $offset = 0, $limit = 8)
    {
        $category = new Category();
        $category = $category->where('sub_cat', $id)->first();
        $products = Product::whereIn('category_id', [$id, @$category->id]);
        Product::$products_count = $products->count();
        return $products->limit($limit)->offset($offset)->get();
    }

    static function getPageCount()
    {
        return ceil(Product::$products_count / Product::$itemPerPage);

    }

    static function updateStock($id, $type = '+' ,$stock = 1){
        $product = Product::where('id', $id)->first();
        $product->stock = $type == '+' ? $product->stock + $stock : $product->stock;
        $product->stock = $type == '-' ? $product->stock - $stock : $product->stock;
        if($product->save())
            return true;
        else
            return false;
    }

}
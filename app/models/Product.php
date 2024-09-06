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

    function getProducts($limit = ITEM_PER_PAGE, $order = 'created_at', $order_type = 'desc', $offset = 0)
    {
        $products = $this->where('id', '!=', '0')->orderBy($order, $order_type);
        Product::$products_count = $products->count();
        return $products->limit($limit)->offset($offset)->get();
    }

    static function setProductsCount()
    {
        if (User::isUserWriter() && strstr($_SERVER['REDIRECT_URL'], 'panel'))
            Product::$products_count = Product::getUserProducts($_SESSION['user_id'])->count();
        else
            Product::$products_count = Product::getAllProducts()->count();
    }

    static function deleteProduct($id)
    {
        $product = Product::getProductById($id);
        if ($product->delete())
            return true;
        else
            return false;
    }

    static function deleteThumbnail($id)
    {
        $product = Product::getProductById($id);
        if (file_exists($product->thumbnail))
            return unlink($product->thumbnail);
        return false;
    }

    static function getAllProducts()
    {
        return Product::where('id', '!=', '0')->orderBy('created_at', 'desc')->get();
    }

    static function getProductById($id)
    {
        return Product::where('id', $id)->first();
    }

    static function getUserProducts($user_id = 0, $limit = 0, $offset = 0)
    {
        if ($user_id > 0)
            $products = Product::where('user_id', $user_id);
        else
            $products = Product::where('id', '!=', '0');
        if ($limit > 0)
            $products = $products->limit($limit);
        if ($offset > 0)
            $products = $products->offset($offset);
        return $products->orderBy('created_at', 'desc')->get();
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

    function getSearchProducts($params = 0, $order = 'created_at', $order_type = 'desc', $offset = 0, $limit = 8)
    {
        if (!$params == 0) {
            $search = ['title', 'LIKE', "%$params[0]%"];
            $category = new Category();
            if ($params[1] > 1)
                $categories = $category->where('sub_cat', $params[1])->get();
            else
                $categories = Category::getAllCategories();
            $category_ids = [$params[1]];

            foreach ($categories as $category) {
                $category_ids[] = $category->id;
            }
            $category = [array_values($category_ids)][0];
            $price_start = $params[2] != 0 ? ['price', '>=', $params[2] * 10] : ['price', '!=', -1];
            $price_end = $params[3] != 0 ? ['price', '<=', $params[3] * 10] : ['price', '!=', -1];
            $stock = $params[4] ? ['stock', '>', 0] : ['stock', '>=', 0];
            $products = $this->where([$search, $price_start, $price_end, $stock]);
            $products = $products->whereIn('category_id', $category);
            $products = $products->orderBy($order, $order_type);
        } else
            $products = Product::where('id', '!=', '0')->orderBy($order, $order_type);
        if (User::isUserLogin() && User::isUserWriter() && strstr($_SERVER['REDIRECT_URL'], 'panel'))
            $products->where('user_id', $_SESSION['user_id']);

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

    static function getProfit($productId, $format = true)
    {
        if ($format)
            return number_format(Product::getPrice($productId, false) - Product::getSalePrice($productId, false));
        else
            return Product::getPrice($productId, false) - Product::getSalePrice($productId, false);
    }

    static function getProfitPercent($productId, $format = true)
    {
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

    static function getCategoryProduct($cat_id)
    {
        return Category::where('id', $cat_id)->first();
    }

    static function getMainCategoryProduct($cat_id)
    {
        $category = Category::getMainCategory(Product::getCategoryProduct($cat_id)->id);
        return $category ? $category : 'اصلی';
    }

    static function getPageCount()
    {
        return ceil(Product::$products_count / Product::$itemPerPage);
    }

    static function updateStock($id, $type = '+', $stock = 1)
    {
        $product = Product::where('id', $id)->first();
        $product->stock = $type == '+' ? $product->stock + $stock : $product->stock;
        $product->stock = $type == '-' ? $product->stock - $stock : $product->stock;
        if ($product->save())
            return true;
        else
            return false;
    }

    static function updateSellCount($id, $type = '+', $stock = 1)
    {
        $product = Product::where('id', $id)->first();
        $product->sale_count = $type == '+' ? $product->sale_count + $stock : $product->sale_count;
        $product->sale_count = $type == '-' ? $product->sale_count - $stock : $product->sale_count;
        if ($product->save())
            return true;
        else
            return false;
    }

}
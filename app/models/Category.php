<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    protected $primaryKey = 'id';

    static function getAllCategories()
    {
        return Category::where('id', '!=', 0)->orderBy('created_at', 'desc')->get();
    }

    function getCategories()
    {
        return $this->where('sub_cat', 0)->get();
    }

    static function deleteCategory($id)
    {
        $category = Category::getCategoryById($id);
        if (Category::hasSubCategories($category->id)) {
            $subCategories = Category::getSubCategories($category->id);
            foreach ($subCategories as $subCategory) {
                $subCategory->delete();
            }
        }
        if ($category->delete())
            return true;
        else
            return false;

    }

    function getSubCategories($id)
    {
        return $this->where('sub_cat', $id)->get();
    }

    static function getAllMainCategory()
    {
        return Category::where('sub_cat', 0)->orderBy('created_at', 'desc')->get();
    }

    static function hasSubCategories($id)
    {
        return Category::where('sub_cat', $id)->count() > 0;
    }

    static function getCategoryById($id)
    {
        return Category::where('id', $id)->first();
    }

    static function getCountSubCategories($id)
    {
        $subCategories = Category::where('sub_cat', $id)->get();
        $count = 0;
        foreach ($subCategories as $subCategory) {
            $count += Product::where('category_id', $subCategory->id)->count();
        }
        return $count;
    }

    static function getMainCategory($id)
    {
        $category = Category::where('id', $id)->first();
        $mainCategory = Category::where('id', $category->sub_cat)->first();
        return $mainCategory;
    }
}
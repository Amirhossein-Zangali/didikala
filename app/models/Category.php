<?php

namespace didishop\models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    protected $primaryKey = 'id';

    function getCategories()
    {
        return $this->where('sub_cat', 0)->get();
    }

    function getSubCategories($id)
    {
        return $this->where('sub_cat', $id)->get();
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
}
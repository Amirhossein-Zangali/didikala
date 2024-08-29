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
        return self::where('sub_cat', $id)->count() > 0;
    }
}
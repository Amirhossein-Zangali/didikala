<?php

namespace didishop\models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = false;
    protected $primaryKey = 'id';

    function getProducts()
    {
        return $this->where('id', '!=', '0')->orderBy('created_at', 'desc')->get();
    }

    function getSliderProducts($limit = 6)
    {
        return $this->where('discount_percent', '>', '0')->orderBy('discount_percent', 'desc')->limit($limit)->get();
    }
}
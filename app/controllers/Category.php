<?php
namespace didikala\controllers;

use didikala\libraries\Controller;
use didikala\models\Product;

require_once "../app/bootstrap.php";

class Category extends Controller
{
    public function __construct()
    {
        $this->categoryModel = $this->model('Category');
    }

    public function index($id){
        $data = [
            'products' => Product::getCategoryProducts($id)
        ];

        $this->view('category/index', $data);
    }

}
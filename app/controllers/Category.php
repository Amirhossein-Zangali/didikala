<?php
namespace didikala\controllers;

use didikala\libraries\Controller;
use didikala\models\Product;

require_once "../app/bootstrap.php";

class Category extends Controller
{
    public function index($id){
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * 8;
        $data = [
            'products' => Product::getCategoryProducts($id, $offset),
            'page' => $page,
            'offset' => $offset
        ];

        $this->view('category/index', $data);
    }
}
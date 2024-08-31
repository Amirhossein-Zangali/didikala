<?php
namespace didikala\controllers;

use didikala\libraries\Controller;

require_once "../app/bootstrap.php";

class Product extends Controller
{
    public function __construct()
    {
        $this->productModel = $this->model('Product');
    }

    public function index(){
        $order = $_GET['order'] ?? 'created_at';
        $order_type = 'desc';

        if ($order){
            if ($order == 'top_seller')
                $order = 'sale_count';
            else if ($order == 'top_discount')
                $order = 'discount_percent';
            else if ($order == 'low_price') {
                $order = 'price';
                $order_type = 'asc';
            } else if ($order == 'high_price')
                $order = 'price';
            else
                $order = 'created_at';
        } else
            $order = ['created_at', 'desc'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize Post data
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_SPECIAL_CHARS);
            $_GET = filter_input_array(INPUT_GET,FILTER_SANITIZE_SPECIAL_CHARS);

            $search = isset($_POST['search']) ? trim($_POST['search']) : '';
            $category = isset($_POST['category']) ? intval($_POST['category']) : 0;
            $price_start = isset($_POST['price_start']) ? intval($_POST['price_start']) : 0;
            $price_end = isset($_POST['price_end']) ? intval($_POST['price_end']) : 0;
            $stock = isset($_POST['stock']);

            $params = [$search, $category, $price_start, $price_end, $stock];

            $data = [
                'products' => $this->productModel->getSearchProducts($params, $order, $order_type),
                $params
            ];
            $this->view('product/index',$data);


        } else {
            $data = [
                'products' => $this->productModel->getProducts(12, $order, $order_type)
            ];

            $this->view('product/index', $data);
        }
    }

}
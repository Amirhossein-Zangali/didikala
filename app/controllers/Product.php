<?php

namespace didikala\controllers;

use didikala\libraries\Controller;

require_once "../app/bootstrap.php";

class Product extends Controller
{
    public function __construct()
    {
        $this->productModel = $this->model('Product');
        $this->commentModel = $this->model('Comment');
    }

    public function index()
    {
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * 8;

        $order = $_GET['order'] ?? 'created_at';
        $order_type = 'desc';
        if ($order) {
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
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $search = isset($_POST['search']) ? trim($_POST['search']) : '';
            $category = isset($_POST['category']) ? intval($_POST['category']) : 0;
            $price_start = isset($_POST['price_start']) ? intval($_POST['price_start']) : 0;
            $price_end = isset($_POST['price_end']) ? intval($_POST['price_end']) : 0;
            $stock = isset($_POST['stock']);

            $params = [$search, $category, $price_start, $price_end, $stock];

            $data = [
                'products' => $this->productModel->getSearchProducts($params, $order, $order_type, $offset),
                $params,
                'page' => $page,
                'offset' => $offset
            ];
            $this->view('product/index', $data);


        } else {
            $data = [
                'products' => $this->productModel->getProducts(8, $order, $order_type, $offset),
                'page' => $page,
                'offset' => $offset
            ];
            $this->view("product/index", $data);
        }
    }

    public function detail($id = 0)
    {
        $data = [
            'products' => $this->productModel->getProduct($id)
        ];

        $this->view('product/detail', $data);
    }

    public function comment($id = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $strengths = isset($_POST['strengths']) ? trim($_POST['strengths']) : '';
            $weaknesses = isset($_POST['weaknesses']) ? trim($_POST['weaknesses']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $commendation = isset($_POST['commendation']) ? intval($_POST['commendation']) : 0;
            $user_id = $_SESSION['user_id'];
            $product_id = $id;
            $params = [$user_id, $product_id, $strengths, $weaknesses, $content, $commendation];

            $data = [
                'comment' => $this->commentModel->addComment($params),
                'products' => $this->productModel->getProduct($id)
            ];
            flash('comment_success', 'نظر با موفقیت ثبت و پس از تایید نمایش داده می شود.');
            $this->view("product/detail", $data);

        } else {
            $data = [
                'products' => $this->productModel->getProduct($id)
            ];

            $this->view('product/comment', $data);
        }
    }

    public function question($id = 0)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            if (isset($_POST['reply'])){
                $data = [
                    'products' => $this->productModel->getProduct($id),
                    'reply' => $_POST['reply']
                ];

                $this->view('product/question', $data);
            }else if (isset($_POST['question'])) {
                $content = trim($_POST['question']);
                $user_id = $_SESSION['user_id'];
                $product_id = $id;
                $reply = $_POST['reply_id'] ? intval($_POST['reply_id']) : 0;
                $status = $_POST['reply_id'] ? 1 : 0;

                $params = [$user_id, $product_id, $content];

                $data = [
                    'comment' => $this->commentModel->addQuestion($params, $reply, $status),
                    'products' => $this->productModel->getProduct($id)
                ];
                if ($_POST['reply_id'])
                    flash('comment_success', 'پاسخ با موفقیت ثبت شد.');
                else
                    flash('comment_success', 'پرسش با موفقیت ثبت و پس از تایید نمایش داده می شود.');

                $this->view("product/detail", $data);
            }
        }
        $data = [
            'products' => $this->productModel->getProduct($id)
        ];

        $this->view('product/question', $data);
    }

}
<?php

namespace didikala\controllers;

use didikala\libraries\Controller;
use didikala\models\Comment;
use didikala\models\Order;
use didikala\models\User;
use didikala\models\UserPermission;
use didikala\models\Category;
use didikala\models\Product;
use Doctrine\Inflector\InflectorFactory;

require_once "../app/bootstrap.php";

class Panel extends Controller
{
    public function index()
    {
        $this->view('panel/index');
    }

    public function orders()
    {
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * Order::$itemPerPage;

        Order::setOrdersCount();

        $data = ['page' => $page, 'offset' => $offset];
        if (isset($_POST['id'])) {
            $order = Order::getOrder($_POST['id']);
            $data = ['order' => $order];
        }
        $this->view('panel/orders', $data);
    }

    public function users()
    {
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * User::$itemPerPage;

        User::setUsersCount();

        $data = ['page' => $page, 'offset' => $offset];

        if (isset($_POST['search'])) {
            $data = ['search' => $_POST['search'], 'field' => $_POST['field']];
        }

        if (isset($_POST['delete'])) {
            if (User::where('id', $_POST['delete'])->delete()){
                $this->view('panel/users', $data);
            }
        }

        if (isset($_POST['update'])) {
            if ($_POST['role']){
                $user = User::getUser($_POST['update']);
                $user->role = $_POST['role'];
                $user->save();
            }
            $permissions = $_POST['permission'] ?? false;
            if ($permissions){
                UserPermission::addUserPermissions($_POST['update'], $permissions);
                $this->view('panel/users', $data);
            }
        }

        if (isset($_POST['id'])) {
            $user = User::getUser($_POST['id']);
            $data = ['user' => $user];
        }
        $this->view('panel/users', $data);
    }

    public function comments()
    {
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * Comment::$itemPerPage;

        $data = ['page' => $page, 'offset' => $offset];

        Comment::setCommentsCount();

        if (isset($_POST['update'])) {
            $comment = Comment::where('id', $_POST['update'])->first();
            $comment->status = $_POST['status'];
            if ($comment->save())
                $this->view('panel/comments');
        }
        if (isset($_POST['delete'])) {
            Comment::deleteComment($_POST['delete']);
            $this->view('panel/comments');
        }
        $this->view('panel/comments', $data);
    }

    public function questions()
    {
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * Comment::$itemPerPage;

        $data = ['page' => $page, 'offset' => $offset];

        Comment::setQuestionsCount();

        if (isset($_POST['update'])) {
            $comment = Comment::where('id', $_POST['update'])->first();
            $comment->status = $_POST['status'];
            if ($comment->save())
                $this->view('panel/questions');
        }
        if (isset($_POST['delete'])) {
            Comment::deleteComment($_POST['delete']);
            $this->view('panel/questions');
        }
        $this->view('panel/questions', $data);
    }

    public function categories()
    {
        $data = [];
        if (isset($_POST['id'])) {
            if ($_POST['id'] == 0) {
                $data = ['add' => 1];
            } else {
                $category = Category::getCategoryById($_POST['id']);
                $data = ['category' => $category];
            }
        }
        if (isset($_POST['update'])) {
            if ($_POST['update'] == 0)
                $category = new Category();
            else
                $category = Category::where('id', $_POST['update'])->first();
            $category->title = $_POST['title'];
            $category->sub_cat = $_POST['sub_cat'];
            if ($category->save())
                $this->view('panel/categories');
        }
        if (isset($_POST['delete'])) {
            Category::deleteCategory($_POST['delete']);
            $this->view('panel/categories');
        }
        $this->view('panel/categories', $data);
    }

    public function products()
    {
        $page = $_POST['page'] ?? 1;
        $offset = ($page - 1) * Product::$itemPerPage;
        Product::setProductsCount();
        $data = ['page' => $page, 'offset' => $offset];

        if (isset($_POST['id'])) {
            if ($_POST['id'] == 0) {
                $data = ['add' => 1];
            } else {
                $product = Product::getProductById($_POST['id']);
                $data = ['product' => $product];
            }
        }
        if (isset($_POST['update'])) {
            if ($_POST['update'] == 0)
                $product = new Product();
            else
                $product = Product::where('id', $_POST['update'])->first();
            if ($_FILES['thumbnail']['size']){
                $thumbnail = $_FILES['thumbnail'];
                $thumbnail_extension = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
                $thumbnail_path = 'uploads/products/';
                $thumbnail_name = $thumbnail_path . str_replace('.' . $thumbnail_extension, '_', $thumbnail['name']) . time() . '.' . $thumbnail_extension;
                if (isset($product->thumbnail))
                    Product::deleteThumbnail($_POST['update']);
                if (move_uploaded_file($thumbnail['tmp_name'], $thumbnail_name))
                    $product->thumbnail = $thumbnail_name;
            }
            $product->title = $_POST['title'];
            $product->content = $_POST['content'];
            $product->user_id = $_SESSION['user_id'];
            $product->category_id = $_POST['category_id'];
            $product->price = $_POST['price'];
            $product->discount_percent = $_POST['discount_percent'];
            $product->stock = $_POST['stock'];
            if ($product->save())
                $this->view('panel/products');
        }
        if (isset($_POST['delete'])) {

            if (Product::deleteThumbnail($_POST['delete']))
                Product::deleteProduct($_POST['delete']);
            $this->view('panel/products');
        }
        $this->view('panel/products', $data);
    }

    public function info()
    {
        $data = [];
        if (isset($_POST['update'])) {
            if (empty($_POST['password'])) {
                if (!strlen($_POST['phone']) == 11 && !is_numeric($_POST['phone']))
                    $data = ['err' => 'شماره موبایل بابد 11 رقم باشد'];
                if (User::findUserByPhone($_POST['phone'], $_SESSION['user_id']))
                    $data = ['err' => 'شماره موبایل قبلا ثبت شده!'];
                if (User::findUserByEmail($_POST['email'], $_SESSION['user_id']))
                    $data = ['err' => 'ایمیل قبلا ثبت شده!'];
                if (User::findUserByUsername($_POST['username'], $_SESSION['user_id']))
                    $data = ['err' => 'نام کاربری قبلا ثبت شده!'];
                if (!isset($data['err'])) {
                    $user = User::where('id', $_SESSION['user_id'])->first();
                    $user->name = $_POST['name'];
                    $user->username = $_POST['username'];
                    if ($user->email != $_POST['email'])
                        $user->email_verify = 0;
                    $user->email = $_POST['email'];
                    $user->phone = $_POST['phone'];
                    if ($user->save())
                        $this->view('panel/info');
                }
            } else if (!empty(trim($_POST['new_password']))) {
                if (!strlen($_POST['phone']) == 11 && !is_numeric($_POST['phone']))
                    $data = ['err' => 'شماره موبایل بابد 11 رقم باشد'];
                if (User::findUserByPhone($_POST['phone'], $_SESSION['user_id']))
                    $data = ['err' => 'شماره موبایل قبلا ثبت شده!'];
                if (User::findUserByEmail($_POST['email'], $_SESSION['user_id']))
                    $data = ['err' => 'ایمیل قبلا ثبت شده!'];
                if (User::findUserByUsername($_POST['username'], $_SESSION['user_id']))
                    $data = ['err' => 'نام کاربری قبلا ثبت شده!'];
                if (!isset($data['err'])) {
                    $user = User::where('id', $_SESSION['user_id'])->first();
                    $user->name = $_POST['name'];
                    $user->username = $_POST['username'];
                    $user->email = $_POST['email'];
                    $user->phone = $_POST['phone'];
                    if (password_verify($_POST['password'], $user->password)) {
                        $user->password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                        if ($user->save()) {
                            $data = ['pass' => 'رمز عبور با موفقیت تغییر کرد.'];
                            $this->view('panel/info', $data);
                        }
                    } else
                        $data = ['err' => 'رمز عبور نادرست است.'];
                }
            }
        }

        $this->view('panel/info', $data);
    }
}
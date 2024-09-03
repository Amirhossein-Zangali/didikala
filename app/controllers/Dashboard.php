<?php

namespace didikala\controllers;

use didikala\libraries\Controller;
use didikala\models\Comment;
use didikala\models\Order;
use didikala\models\User;

require_once "../app/bootstrap.php";

class Dashboard extends Controller
{

    public function index()
    {
        $this->view('dashboard/index');
    }

    public function orders()
    {
        $data = [];
        if (isset($_POST['id'])) {
            $order = Order::getOrder($_POST['id']);
            $data = ['order' => $order];
        }
        $this->view('dashboard/orders', $data);
    }

    public function favorites()
    {
        $this->view('dashboard/favorites');
    }

    public function comments()
    {
        if (isset($_POST['delete'])) {
            Comment::deleteComment($_POST['delete']);
            redirect('dashboard/comments');
        }
        $this->view('dashboard/comments');
    }

    public function addresses()
    {
        $data = [];
        if (isset($_POST['update'])) {
            if (strlen($_POST['post_code']) == 10 && is_numeric($_POST['post_code'])) {
                $user = User::where('id', $_SESSION['user_id'])->first();
                $user->address = $_POST['address'];
                $user->post_code = $_POST['post_code'];
                $user->save();
                $this->view('dashboard/addresses');
            } else
                $data = ['err' => 'کدپستی یابد 10 زفم باشد'];
        }
        $this->view('dashboard/addresses', $data);
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
                    $user->email = $_POST['email'];
                    $user->phone = $_POST['phone'];
                    if ($user->save())
                        $this->view('dashboard/info');
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
                            $this->view('dashboard/info', $data);
                        }
                    } else
                        $data = ['err' => 'رمز عبور نادرست است.'];
                }
            }
        }

        $this->view('dashboard/info', $data);
    }

    public
    function verify_email()
    {
        $data = [];
        if (isset($_POST['sendEmail'])) {
            if (User::sendActivationEmail($_POST['sendEmail'])) {
                $this->view('dashboard/verify_email');
            }
        }
        if (isset($_POST['active'])) {
            if ($_POST['code'] == $_SESSION['email_code']) {
                $user = User::where('id', $_SESSION['user_id'])->first();
                $user->email_verify = 1;
                if ($user->save())
                    redirect('dashboard/');
            } else
                $data = ['err' => 'کد فعال سازی نادرست است.'];
        }
        $this->view('dashboard/verify_email', $data);
    }

}
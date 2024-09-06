<?php
namespace didikala\controllers;

use AllowDynamicProperties;
use didikala\libraries\Controller;
use didikala\models\User;


require_once "../app/bootstrap.php";

#[AllowDynamicProperties] class Pages extends Controller
{
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(){
        $this->view('pages/index');
    }

    public function register()
    {
        // Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process Form
            // Sanitize Post data
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_SPECIAL_CHARS);

            // Init Data
            $data = [
                'name' => trim($_POST['name']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'email_err' => ''
            ];

            // Validate Username
            if( $this->userModel->findUserByUsername($data['username']) ){
                $data['username_err'] = 'نام کاربری قبلا انتخاب شده';
            }

            // Validate Email
            if( $this->userModel->findUserByEmail($data['email']) ){
                $data['email_err'] = 'ایمیل قبلا انتخاب شده';
            }

            // Make Sure errors empty
            if(
                empty($data['username_err']) &&
                empty($data['email_err'])
            ){

                // Hash Password
                $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);

                // Register User
                if( $this->userModel->register($data) ){
                    flash('register_success', 'شما عضو سایت شده اید و میتوانید وارد شوید');
                    redirect('pages/login');
                }else{
                    die('Error User Registration');
                }
            }else{
                // Load VIew Register with errors
                $this->view('pages/register',$data);
            }


        } else {
            // Init Data
            $data = [
                'name' => '',
                'username' => '',
                'email' => '',
                'password' => '',
                'username_err' => '',
                'email_err' => ''
            ];

            $this->view('pages/register', $data);
        }
    }

    public function login()
    {
        // Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process Form
            $_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_SPECIAL_CHARS);

            // Init Data
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if(empty($data['username'])){
                $data['username_err'] = 'لطفا نام کاربری خود را وارد کنید';
            }else if(! $this->userModel->findUserByUsername($data['username'])){
                // User Not Found
                $data['email_err'] = 'نام کاربری یا رمز عبور اشتباه است';
            }

            // Make Sure errors empty
            if(
                empty($data['email_err'])
            ){
                // Validated
                $loggedInUser = $this->userModel->login($data);
                if($loggedInUser){
                    // Create session
                    $this->createUserSession($loggedInUser);
                }else{
                    $data['password_err'] = 'پسورد را اشتباه وارد کرده اید';
                    $this->view('pages/login',$data);
                }
            }else{
                // Load VIew Register with errors
                $this->view('pages/login',$data);
            }

        } else {
            // Init Data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            $this->view('pages/login', $data);
        }
    }

    public function createUserSession($id)
    {
        $_SESSION['user_id'] = $id;

        redirect();
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        session_destroy();
        redirect('pages/login');
    }
}
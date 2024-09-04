<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;
use PHPMailer\PHPMailer\PHPMailer;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;
    protected $primaryKey = 'id';

    static public $users_count = 0;
    static public $itemPerPage = ITEM_PER_PAGE;

    static function setUsersCount()
    {
        User::$users_count = User::getAllUsers()->count();
    }

    static function searchByField($field, $search)
    {
        if ($field == 'role') {
            if ($search == 'ادمین')
                $search = 'admin';
            if ($search == 'نویسنده')
                $search = 'writer';
            if ($search == 'کاربر')
                $search = 'user';
        }
        return User::where($field, 'LIKE', '%' . $search . '%')->get();
    }

    static function getUser($user_id)
    {
        return User::where('id', $user_id)->first();
    }

    static function getPageCount()
    {
        return ceil(User::$users_count / User::$itemPerPage);
    }

    static function getRoll($user_id, $fa = 0)
    {
        $user = User::where('id', $user_id)->first();
        if ($user->role == 'user')
            $role = $fa > 0 ? 'کاربر' : 'user';
        if ($user->role == 'writer')
            $role = $fa > 0 ? 'نویسنده' : 'writer';
        if ($user->role == 'admin')
            $role = $fa > 0 ? 'ادمین' : 'admin';
        return $role;
    }

    static function getOrderCount($user_id)
    {
        return Order::where('user_id', $user_id)->where('status', 'completed')->count();
    }

    static function getAllUsers($limit = 0, $offset = 0)
    {
        if ($offset > 0)
            return User::where('id', '!=', $_SESSION['user_id'])->orderBy('created_at', 'desc')->limit($limit)->offset($offset)->get();
        if ($limit > 0)
            return User::where('id', '!=', $_SESSION['user_id'])->orderBy('created_at', 'desc')->limit($limit)->get();
        else
            return User::where('id', '!=', $_SESSION['user_id'])->orderBy('created_at', 'desc')->get();
    }

    static function canManageUser()
    {
        return UserPermission::hasPermission('مدیریت_کاربر_ها', $_SESSION['user_id']);
    }

    static function canManagePermission()
    {
        return UserPermission::hasPermission('مدیریت_اجازه_ها', $_SESSION['user_id']);
    }

    static function canManageProduct()
    {
        return UserPermission::hasPermission('مدیریت_محصول_ها', $_SESSION['user_id']);
    }

    static function canManageComment()
    {
        return UserPermission::hasPermission('مدیریت_نظر_ها', $_SESSION['user_id']);
    }

    static function canManageQuestion()
    {
        return UserPermission::hasPermission('مدیریت_پرسش_ها', $_SESSION['user_id']);
    }

    static function canManageCategory()
    {
        return UserPermission::hasPermission('مدیریت_دسته_بندی_ها', $_SESSION['user_id']);
    }

    static function canManageOrder()
    {
        return UserPermission::hasPermission('مدیریت_سفارش_ها', $_SESSION['user_id']);
    }
    static function isUserLogin()
    {
        $login = $_SESSION['user_id'] ?? false;
        return $login;
    }

    static function isUserWriter()
    {
        $user = User::where('id', $_SESSION['user_id'])->first();
        return $user->role == 'writer';
    }

    static function isProductWriter($product_id)
    {
        return Product::where('id', $product_id)->where('user_id', $_SESSION['user_id'])->exists();
    }

    static function isUserAdmin()
    {
        $user = User::where('id', $_SESSION['user_id'])->first();
        return $user->role == 'admin';
    }

    static function isUserUser($id)
    {
        $user = User::where('id', $id)->first();
        return $user->role == 'user';
    }

    static function isUser()
    {
        $user = User::where('id', $_SESSION['user_id'])->first();
        return $user->role == 'user';
    }

    static function isWriter($id)
    {
        $user = User::where('id', $id)->first();
        return $user->role == 'writer';
    }

    static function isAdmin($id)
    {
        $user = User::where('id', $id)->first();
        return $user->role == 'admin';
    }

    static function haveAddress($id)
    {
        $user = User::where('id', $id)->first();
        return $user->address;
    }

    static function findPhone($phone)
    {
        return User::where('phone', $phone)->count() > 0;
    }

    static function findUsername($username)
    {
        return User::where('username', $username)->count() > 0;
    }

    static function findEmail($email)
    {
        return User::where('email', $email)->count() > 0;
    }

    static function sendActivationEmail($email)
    {
        $activationCode = random_int(100000, 999999);
        $_SESSION['email_code'] = $activationCode;

        $mail = new PHPMailer(true);

        try {
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'amirhoseinzangali@gmail.com';
            $mail->Password = 'amir4559';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@didikala.local', 'Didikala');
            $mail->addAddress($email);

            $mail->isHTML(false);
            $mail->Subject = "کد فعالسازی";
            $mail->Body    = "کد فعالسازی شما: " . $activationCode;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    static function havePhone($id)
    {
        $user = User::where('id', $id)->first();
        return $user->phone;
    }

    static function findUserByUsername($username, $user_id = 0)
    {
        return User::where(['username' => $username])->where('id', '!=', $user_id)->exists();
    }

    static function findUserByEmail($email, $user_id = 0)
    {
        return User::where(['email' => $email])->where('id', '!=', $user_id)->exists();
    }

    static function findUserByPhone($phone, $user_id = 0)
    {
        return User::where(['phone' => $phone])->where('id', '!=', $user_id)->exists();
    }

    public function register($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        if ($user->save())
            return $user->id;
        else
            return false;
    }

    public function login($data)
    {
        $user = $this->where('username', $data['username'])->first();
        if ($user) {
            $hash_password = $user->password;
        } else
            $hash_password = '';
        if (password_verify($data['password'], $hash_password)) {
            return $user->id;
        } else {
            return false;
        }
    }
}
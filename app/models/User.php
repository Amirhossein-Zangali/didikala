<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;
use PHPMailer\PHPMailer\PHPMailer;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;
    protected $primaryKey = 'id';

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

    static function isUserAdmin()
    {
        $user = User::where('id', $_SESSION['user_id'])->first();
        return $user->role == 'admin';
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

    public static function sendActivationEmail($email)
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
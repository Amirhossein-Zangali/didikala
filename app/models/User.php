<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;

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

    static function haveAddress($id){
        $user = User::where('id', $id)->first();
        return $user->address;
    }

    static function findUserByUsername($username)
    {
        return User::where(['username' => $username])->exists();
    }

    static function findUserByEmail($email)
    {
        return User::where(['email' => $email])->exists();
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
        if ($user){
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
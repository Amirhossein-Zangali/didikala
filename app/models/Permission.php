<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;
use PHPMailer\PHPMailer\PHPMailer;

class Permission extends Model
{
    protected $table = 'permissions';
    public $timestamps = false;
    protected $primaryKey = 'id';

    static function getPermissions()
    {
        return Permission::where('id', '!=', 0)->orderBy('created_at', 'desc')->get();
    }

    static function getPermission($id)
    {
        return Permission::where('id', $id)->first();
    }

    static function getPermissionByName($name)
    {
        return Permission::where('name', $name)->first();
    }
}
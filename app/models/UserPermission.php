<?php

namespace didikala\models;

use Illuminate\Database\Eloquent\Model;
use PHPMailer\PHPMailer\PHPMailer;

class UserPermission extends Model
{
    protected $table = 'user_permissions';
    public $timestamps = false;
    protected $primaryKey = 'id';

    static function addUserPermissions($user_id, $permissions)
    {
        UserPermission::where('user_id', $user_id)->delete();
        foreach ($permissions as $permission) {
            $userPermission = new UserPermission();
            $userPermission->user_id = $user_id;
            $userPermission->permission_id = $permission;
            $userPermission->save();
        }
    }
    static function getUserPermissions($user_id)
    {
        return UserPermission::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
    }
    static function getUserPermissionsId($user_id)
    {
        $user_permissions = UserPermission::getUserPermissions($user_id);
        $user_permissions_id = [];
        foreach ($user_permissions as $user_permission) {
            $user_permissions_id[] = $user_permission->permission_id;
        }
        return $user_permissions_id;
    }

    static function hasPermission($permission, $user_id)
    {
        $permission_id = Permission::getPermissionByName($permission)->id;
        return UserPermission::where('permission_id', $permission_id)->Where('user_id', $user_id)->exists();
    }
}
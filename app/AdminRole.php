<?php

namespace App;

use App\Model;

class AdminRole extends Model
{
    protected $table = "admin_roles";
    
    public function permissions()
    {
        return $this->belongsToMany(\App\AdminPermission::class, 'admin_permission_role', 'role_id', 'permission_id')->withPivot(['permission_id', 'role_id']);
    }
    
    //赋予权限
    public function grantPermission($permission)
    {
        return $this->permissions()->save($permission);
    }
    
    //取消权限
    public function deletePermission($permission)
    {
        return $this->permissions()->detach($permission);
    }
    
    public function hasPermission($permission)
    {
        return $this->permissions()->contains($permission);
    }
}

<?php

namespace App;

use App\Model;

class AdminPermission extends Model
{
    protected $table = "admin_permissions";
    
    public function roles()
    {
        return $this->belongsToMany(\App\AdminRole::class, 'admin_permission_role', 'permission_id', 'role_id')->withPivot(['permission_id', 'role_id']);
    }
}

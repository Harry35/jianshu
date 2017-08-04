<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use \App\AdminRole;
use \App\AdminPermission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = AdminRole::paginate(10);
         
        return view('/admin/role/index', compact('roles'));
    }
    
    public function create()
    {
        return view('/admin/role/create');
    }
    
    public function store()
    {
        $this->validate(request(), [
            'name'          => 'required|min:3',
            'description'   => 'required'
        ]);
        
        AdminRole::create(request(['name', 'description']));
        
        return redirect('/admin/roles');
    }
    
    public function permission(AdminRole $role)
    {
        $permissions = AdminPermission::all();
        $rolePermissions = $role->permissions;

        return view('admin/role/permission', compact('permissions', 'rolePermissions', 'role'));
    }
    
    public function storePermission(AdminRole $role)
    {
        $this->validate(request(), [
            'permissions' => 'required|array'
        ]);
        
        $permissions = AdminPermission::findMany(request('permissions'));
        $rolePermissions = $role->permissions;
        
        $addPermissions = $permissions->diff($rolePermissions);
        foreach ($addPermissions as $permission) {
            $role->grantPermission($permission);
        }
        
        $deletePermissions = $rolePermissions->diff($permissions);
        foreach ($deletePermissions as $permission) {
            $role->deletePermission($permission);
        }
        
        return back();
    }
}

<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use \App\AdminUser;
use \App\AdminRole;

class UserController extends Controller
{
    public function index()
    {
        $users = AdminUser::paginate(10);
        return view('/admin/user/index', compact('users'));
    }
    
    public function create()
    {
        return view('/admin/user/create');
    }
    
    public function store()
    {
        $this->validate(request(), [
            'name'      => 'required|min:3',
            'password'  => 'required'
        ]);
        
        $name = request('name');
        $password = bcrypt(request('password'));
        AdminUser::create(compact('name', 'password'));
        
        return redirect('/admin/users');
    }
    
    public function role(AdminUser $user)
    {
        $roles = AdminRole::all();
        $myRoles = $user->roles;
        
        return view('/admin/user/role', compact('roles', 'myRoles', 'user'));
    }
    
    public function storeRole(AdminUser $user)
    {
        $this->validate(request(), [
            'roles' => 'required|array'
        ]);
        
        $roles = AdminRole::findMany(request('roles'));
        $myRoles = $user->roles;
        
        $addRoles = $roles->diff($myRoles);
        foreach($addRoles as $role)
        {
            $user->assignRole($role);
        }
        
        $deleteRoles = $myRoles->diff($roles);
        foreach($deleteRoles as $role)
        {
            $user->deleteRole($role);
        }
        
        return back();
    }
}

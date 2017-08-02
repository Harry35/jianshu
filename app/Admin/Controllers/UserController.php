<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use \App\AdminUser;

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
}

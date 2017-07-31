<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin/login/index');
    }
    
    public function login()
    {
        $this->validate(request(), [
            'name'      => 'required',
            'password'  => 'required|min:5|max:10',
        ]);
        //逻辑
        $user = request(['name', 'password']);
        //登录验证
        if (\Auth::guard('admin')->attempt($user)) {
            return redirect('/admin/home');
        }
        
        return redirect()->back()->withErrors("用户名密码不匹配");
    }
    
    public function logout()
    {
        \Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}

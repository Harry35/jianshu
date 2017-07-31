<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view("login/index");
    }
    
    public function login()
    {
        $this->validate(request(), [
            'email'         => 'required|email',
            'password'      => 'required|min:5|max:10',
            'is_remember'   => 'integer'
        ]);
        
        //逻辑
        $user = request(['email', 'password']);
        $is_remember = boolval(request('is_remember'));
        //登录验证
        if (\Auth::attempt($user, $is_remember)) {
            return redirect('/posts');
        }
        
        return redirect()->back();
    }
    
    public function logout()
    {
        \Auth::logout();
        return redirect('/login');
    }
    
}

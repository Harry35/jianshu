<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('/admin/user/index');
    }
    
    public function create()
    {
        return view('/admin/user/add');
    }
    
    public function store()
    {
        
    }
}

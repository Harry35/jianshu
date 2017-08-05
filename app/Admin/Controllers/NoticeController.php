<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use \App\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::all();

        return view('admin/notice/index', compact('notices'));
    }
    
    public function create()
    {
        return view('admin/notice/create');
    }
    
    public function store()
    {
        $this->validate(request(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        
        $notice = Notice::create(request(['title', 'content']));
        //把任务加入队列
        dispatch(new \App\Jobs\SendMessage($notice));
        
        return redirect('/admin/notices');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;

class UserController extends Controller
{
    public function setting()
    {
        return view("user/setting");
    }
    
    public function settingStore()
    {
        
    }
    
    //个人中心页面
    public function show(User $user)
    {
        //个人信息
        $user = User::withCount(['stars', 'fans', 'posts'])->find($user->id);
        //文章列表，取创建时间最新10条
        $posts = $user->posts()->orderBy('created_at', 'desc')->take(10)->get();
        
        //关注用户列表，包含关注／粉丝／文章数
        $stars = $user->stars();
        $susers = User::whereIn('id', $stars->pluck('star_id'))->withCount(['stars', 'fans', 'posts'])->get();
        
        //粉丝用户列表，包含关注／粉丝／文章数
        $fans = $user->fans();
        $fusers = User::whereIn('id', $fans->pluck('fan_id'))->withCount(['stars', 'fans', 'posts'])->get();
        
        return view("user/show", compact('user', 'posts', 'susers', 'fusers'));
    }
    
    public function fan(User $user)
    {
        $me = \Auth::user();
        $me->doFan($user->id);
        
        return [
            'error' => 0,
            'msg'   => ''
        ];
    }
    
    public function unfan(User $user)
    {
        $me = \Auth::user();
        $me->doUnFan($user->id);
        
        return [
            'error' => 0,
            'msg'   => ''
        ];
    }
}

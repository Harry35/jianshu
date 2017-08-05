<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password'
    ];
    
    //用户文章
    public function posts()
    {
        return $this->hasMany(\App\Post::class, 'user_id', 'id');
    }
    
    //用户粉丝
    public function fans()
    {
        return $this->hasMany(\App\Fan::class, 'star_id', 'id');
    }
    
    //用户关注
    public function stars()
    {
        return $this->hasMany(\App\Fan::class, 'fan_id', 'id');
    }
    
    //关注某人
    public function doFan($uid)
    {
        $fan = new \App\Fan();
        $fan->star_id = $uid;
        $this->stars()->save($fan);
    }
    
    //取消关注某人
    public function doUnFan($uid)
    {
        $fan = new \App\Fan();
        $fan->star_id = $uid;
        $this->stars()->delete($fan);
    }
    
    //当前用户是否被uid关注了
    public function hasFan($uid)
    {
        return $this->fans()->where('fan_id', $uid)->count();
    }
    
    //当前用户是否关注了uid
    public function hasStar($uid)
    {
        return $this->stars()->where('star_id', $uid)->count();
    }
    
    public function notices()
    {
        return $this->belongsToMany(\App\Notice::class, 'user_notice', 'user_id', 'notice_id')->withPivot(['user_id', 'notice_id']);
    }
    
    public function addNotice($notice)
    {
        return $this->notices()->save($notice);
    }
}

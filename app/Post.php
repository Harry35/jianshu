<?php

namespace App;

use App\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use Searchable;
    
    public function searchableAs() 
    {
        return "post";
    }
    
    public function toSearchableArray() 
    {
        return [
            'title'     => $this->title,
            'content'   => $this->content
        ];
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('created_at', 'desc');
    }
    
    public function zan($user_id)
    {
        return $this->hasOne(\App\Zan::class)->where('user_id', $user_id);
    }
    
    public function zans()
    {
        return $this->hasMany(\App\Zan::class);
    }
    
    public function postTopics()
    {
        return $this->hasMany(\App\PostTopic::class, 'post_id', 'id');
    }
    
    //属于某个作者的文章
    public function scopeAuthorBy(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
        
    //不属于某个专题的文章
    public function scopeTopicNotBy(Builder $query, $topic_id)
    {
        return $query->doesntHave('postTopics', 'and', function($q) use($topic_id) {
           $q->where('topic_id', $topic_id); 
        });
    }
    
    //全局（默认）scope的方式
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope("available", function(Builder $builder){
            $builder->whereIn('status', [0, 1]);
        });
    }
}

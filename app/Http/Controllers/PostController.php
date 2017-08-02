<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Post;
use \App\Comment;
use \App\Zan;

class PostController extends Controller
{
    public function index()
    {
      $posts = Post::orderBy('created_at', 'desc')->withCount(['comments', 'zans'])->paginate(6);
      return view("post/index", compact('posts'));
    }

    public function show(Post $post)
    {
        //预加载不应在view处理
        $post->load('comments');
        return view("post/show", compact('post'));
    }

    public function create()
    {
      return view("post/create");
    }

    public function store()
    {
      $this->validate(request(), [
        'title'   => 'required|string|max:100|min:5',
        'content' => 'required|string|min:10'
      ]);

      $user_id = Auth::id();
      $params = array_merge(request(['title', 'content']), compact('user_id'));
      $post = Post::create($params);

      return redirect('/posts');
    }

    public function edit(Post $post)
    {
      return view("post/edit", compact('post'));
    }

    public function update(Post $post)
    {
        $this->validate(request(), [
          'title'   => 'required|string|max:100|min:5',
          'content' => 'required|string|min:10'
        ]);
        $this->authorize('update', $post);
        
        $post->title = request('title');
        $post->content = request('content');
        $post->save();

        return redirect('/posts');
    }

    public function delete(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect('/posts');
    }

    public function imageUpload(Request $request)
    {
      $path = $request->file('wangEditorH5File')->storePublicly(md5(time()));

      return asset('storage/' . $path);
    }
    
    public function comment(Post $post)
    {
        $this->validate(request(), [
            'content'   =>  'required|min:3',
        ]);
        
        $comment = new Comment();
        $comment->content = request('content');
        $comment->user_id = auth()->id();

        $post->comments()->save($comment);
        
        return back();
    }
    
    public function zan(Post $post)
    {
        $param = [
            'user_id'   => \Auth::id(),
            'post_id'   => $post->id,
        ];
        
        Zan::firstOrCreate($param);
        return back();
    }
    
    public function unzan(Post $post)
    {
        $post->zan(\Auth::id())->delete();
        return back();
    }
    
        
    public function search()
    {
        $this->validate(request(), [
            'query' => 'required'
        ]);
        
        $query = request('query');
        $posts = Post::search($query)->paginate(2);

        return view("post/search", compact('posts', 'query'));
    }
}

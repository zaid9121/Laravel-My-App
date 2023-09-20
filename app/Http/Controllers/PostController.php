<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    // search
    public function search($term){
            $posts = Post::search($term)->get(); # niversal search syntax. automaticly has access to search
            $posts->load('user:id,username,avatar'); # with this method all given properties will be loaded
            return $posts;

        //return Post::where('title', 'LIKE', '%' . $term . '%')->orWhere('body', 'LIKE', '%' . $term . '%')->with('user:id,username,avatar')->get(); #only for small webpage

    }

    //actuallyUpdate
    public function actuallyUpdate(Post $post, Request $request){ #a
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']); # stripping html tags 
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields); #$post powered by post
        return back()->with('success', 'Post successfully updated.');
    
    }

    //showEditForm method
    public function showEditForm(Post $post){ # fetches existing posts from db 
        return view('edit-post', ['post' => $post]); # blade template has acces to $post 
    }

    // delete 
    public function delete(Post $post){ #Post model
 
        $post->delete(); 
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post successdfully deleted.');
    }

    //view Single Post
    public function viewSinglePost(Post $post){ # this variable name must match variable in root gaven

        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h3><br>'); # overriding body with markdown
        return view('single-post', ['post' => $post]);
    }

    // storeNewPost   
    public function storeNewPost(Request $request){ 
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']); # stripping html tags 
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id(); # 

        $newPost = Post::create($incomingFields); # here stored newly created item in db

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return redirect("/post/{$newPost->id}")->with('success', 'New post successfully created.');
    }

    //store new post API 

    public function storeNewPostApi(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newPost = Post::create($incomingFields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'name' => auth()->user()->username, 'title' => $newPost->title]));

        return $newPost->id;
    }

    // method showCreateForm
    public function showCreateForm(){
        return view('create-post');
    }
}

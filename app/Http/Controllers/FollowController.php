<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    // create follow
    public function createFollow(User $user){
        //you can not follow ureself
        if($user->id == auth()->user()->id){
            return back()->with('failure', 'You cannot follow yourself.');
        }

        // can not followed already followed
        $existCheck = Follow::where([['user_id', '=' , auth()->user()->id],['followeduser', '=', $user->id]])->count(); # checks with 2D array user id = logged in user and followed user = 
        
        if($existCheck){
            return back()->with('failure', 'You are already following that user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save(); # one way to save data in db

        return  back()->with('success', 'User successfully followed.');

        # Follow::create()...; alternative way
    }
    // remove follow
    public function removeFollow(User $user){
        Follow::where([['user_id', '=', auth()->user()->id],['followeduser', '=', $user->id]])->delete();
        return back()->with('success', 'User successfully unfollowed.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use App\Events\OurExampleEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //storeAvatar
    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' .uniqid() . '.jpg';

        $imgData = Image::make($request->file('avatar'))->fit(120)->encode('jpg'); #third party intervention image package. fit() resizes image with specified dimensions and encode() converts in given method ---> Hammer^^
        Storage::put('public/avatars/' . $filename, $imgData ); # laravel-->  simplified interface for interaction of storing, retrieving, and deleting files.

        #$request->file('avatar')->store('public/avatars'); --> also possible this way

        $oldAvatar = $user->avatar; #

        $user->avatar = $filename; 
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg"){ # 
            Storage::delete(str_replace("/storage/", "public/" , $oldAvatar)); # str_replace method  with 3 arguments 
        }
        return back()->with('success', 'Congrats with new avatar.'); #redirects back user wtih new avatar
    }

    // showAvatarForm
    public function showAvatarForm(){
        return view('avatar-form');
    }

    
    // public function profile(User $user){ # 1.with profile function accecing user and blog posts in db 2.laravel looks up something in db makes it with id number but here is variable $user.
    //     $currentlyFollowing = 0;
    

    //     if(auth()->check()){
    //         $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id],['followeduser', '=' , $user->id]])->count();
    //     }
        
    //     return view('profile-posts', ['currentlyFollowing' => $currentlyFollowing,'avatar' => $user->avatar ,'username' => $user->username, 'posts'  => $user->posts()->latest()->get(), 'postCount' => $user->posts()->count()]);
    // }

    //get shared data 
    private function getSharedData($user) {
        $currentlyFollowing = 0;

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        } 

        View::share('sharedData', ['currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username, 'postCount' => $user->posts()->count(), 'followerCount' => $user->followers()->count(), 'followingCount' => $user->followingTheseUsers()->count()]); #shared variable can be used in blade
    }
    
    //profile
    public function profile(User $user) {
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }    
    
    //profile Raw
    public function profileRaw(User $user) {
        return response()->json(['theHTML' => view('profile-posts-only', ['posts' => $user->posts()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Profile"]); # allows you to generate HTML output from views or templates in your application
    }   

    //profile Followers
    public function profileFollowers(User $user) {
        $this->getSharedData($user); #calling function 
        # return $user->followers()->latest()->get(); # debug
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }

    //profile followers Raw
    public function profileFollowersRaw(User $user) {
        return response()->json(['theHTML' => view('profile-followers-only', ['followers' => $user->followers()->latest()->get()])->render(), 'docTitle' => $user->username . "'s Followers"]);
    }

    //profile following 
    public function profileFollowing(User $user) {
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingTheseUsers()->latest()->get()]);
    }

    //profile following  Raw
    public function profileFollowingRaw(User $user) {
        return response()->json(['theHTML' => view('profile-following-only', ['following' => $user->followingTheseUsers()->latest()->get()])->render(), 'docTitle' => 'Who ' . $user->username . " Follows"]); # this method generates a JSON response that includes the HTML representation of the 'profile-following-only' view and a document title based on the provided user's information
    }

    // logout 
    // public function logout(){
    //     event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
    //     auth()->logout();
    //     return redirect('/')->with('success', 'You are now logged out.'); # redircts to homepage in this case - method from laravel
    // }

    public function logout() {
        event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out.');
    }

    //shows showCorrectHomepage method checks signed user is true
     public function showCorrectHomepage() {
        if (auth()->check()) { # auth()->check() method checks user logged in or not.
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
        } else {
            $postCount = Cache::remember('postCount', 20, function() {
                
                return Post::count();
            });
            return view('homepage', ['postCount' => $postCount]);
        }
    }

    //login api
    public function loginApi(Request $request) {
        $incomingFields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt($incomingFields)) {
            $user = User::where('username', $incomingFields['username'])->first(); #first() gives back actual user
            $token = $user->createToken('ourapptoken')->plainTextToken;
            return $token;
        }
        return 'sorry';
    }

    //Login method
    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate(); #method session returns obj. $request->session()->regenerate() is used to refresh the session ID and enhance session security by generating a new session identifier for the user. 

            event(new OurExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));
            
            return redirect('/')->with('success','You have successfully logged in.'); # with method is used to store data in the session for a single request.

        }else{
            return redirect('/')->with('failure', 'Invalid login.');
        }
    
    }

    //method for register
    public function register(Request $request){#incoming data from form will be saved in $request 
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users','username')],#Rule is utilation laravel class which provides a set of validations 
            'email' => [ 'required', 'email', Rule::unique('users','email')],
            'password' => ['required', 'min:3', 'confirmed']        
        ]); #built in validate method 
        
        $incomingFields['password'] = bcrypt($incomingFields['password']); #crypting password with laravelfunction bcrypt ->hammmer 
        $user =  User::create($incomingFields); #
        auth()->login($user); # regisetered user will be logged in automaticaly.
        return redirect('/')->with('success', 'Thank you for creating account.');
    }
}

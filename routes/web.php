<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;


//Users related routes 

//admin Gate
Route::get('/admins-only', function(){ 
    // if(Gate::allows('visitAdminPages')){
    //     return 'YOU ARE NOT ALLOWED';
    // }
    return'ONLY FOR ADMINS';
})->middleware('can:visitAdminPages'); #not a good practice --> function in code. Gate is used for ability like true or false. inspite of policy is designed for crud
# Route::get('test', function(){return 'test';});
//shows homepage after logging in

Route::get('/', [UserController::class, "showCorrectHomepage" ])->name('login');
// register
Route::post('/register', [UserController::class, 'register'] )->middleware('guest');
// login
Route::post('/login', [UserController::class, 'login'] )->middleware('guest');
// logout
Route::post('/logout', [UserController::class, 'logout'] )->middleware('mustBeLoggedIn');
// show avatar
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('mustBeLoggedIn');
// sent avatar
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('mustBeLoggedIn');

//Follow related routes
// create follow
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'] )->middleware('mustBeLoggedIn');
// remove follow
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow'] )->middleware('mustBeLoggedIn');

// Blog Post related routes
// shows create form for creating post 
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn'); #'auth' middleware is applied, which means the user must be authenticated to access the route. If the user is not authenticated, they will be redirected to the login page.
// stores new post
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
// routes to post with post id
Route::get('/post/{post}', [PostController::class, 'viewSinglePost']);
// delete post
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post'); # only passed middelware user can delete post
// edit post
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
// submit form
Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');

//Search realted routs
//search
Route::get('/search/{term}', [PostController::class, 'search']);

//Profile related routs
//profile
Route::get('/profile/{user:username}', [UserController::class, 'profile']); 
//profile followers
Route::get('/profile/{user:username}/followers', [UserController::class, 'profileFollowers']); 
//profile is following
Route::get('/profile/{user:username}/following', [UserController::class, 'profileFollowing']); 


Route::middleware('cache.headers:public;max_age=20;etag')->group(function() {
//profile Raw
Route::get('/profile/{user:username}/raw', [UserController::class, 'profileRaw']); 
//profile followers Raw
Route::get('/profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw']); 
//profile is following Raw
Route::get('/profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw']); 
});

//Chat Route

Route::post('/send-chat-message', function (Request $request) {
    $formFields = $request->validate([
      'textvalue' => 'required'
    ]);
  
    if (!trim(strip_tags($formFields[' textvalue']))) {
      return response()->noContent();
    }
  
    broadcast(new ChatMessage(['username' =>auth()->user()->username, 'textvalue' => strip_tags($request->textvalue), 'avatar' => auth()->user()->avatar]))->toOthers();
    return response()->noContent(); # broadcast()-> sending broadcast to all connected events
  
  })->middleware('mustBeLoggedIn');
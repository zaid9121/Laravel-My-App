<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];


    protected function avatar(): Attribute { #here function name matters 
        return Attribute::make(get: function($value){ # get avatar value 
            return $value ? '/storage/avatars/' . $value : '/fallback-avatar.jpg'; # determine the path of an avatar image, returning the specific path if available, or a fallback path if not.
        });
    
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //feed posts
    public function feedPosts(){
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser'); #laravel method -> allows to be intermediate table inbetween relationtips. 
    }
    
    // followers 
    public function followers(){
        return $this->hasMany(Follow::class, 'followeduser'); # a user has many follows. in this case who is following a user and local key is 'id' here 
    }
    // following 
    public function followingTheseUsers(){
        return $this->hasMany(Follow::class, 'user_id'); # a user has many follows. in this case who is following a user and foreign key is 'user_id' here 
    }

    public function posts(){ #user has many posts
        return $this->hasMany(Post::class, 'user_id'); #returns relationtips betwen user and post
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    use HasFactory; 

    protected $fillable = ['title', 'body', 'user_id'];

    public function toSearchableArray() { #class name must be exactly this
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id'); #blog post belongs to user
    }
}

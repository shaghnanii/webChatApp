<?php

namespace App\Models\UserActivity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }
}

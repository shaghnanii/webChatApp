<?php

namespace App\Models\UserActivity;
use App\Models\UserActivity\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;


    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }
}

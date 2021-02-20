<?php

namespace App\Models\UserActivity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;


    public function commentable(){
        return $this->morphTo();
    }
}

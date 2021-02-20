<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    public function u_one(){
        return $this->belongsTo(User::class, 'user_one');
    }

    public function u_two(){
        return $this->belongsTo(User::class, 'user_two');
    }
    public function messages(){
        return $this->hasMany(Message::class, 'conversation_id');
    }

}

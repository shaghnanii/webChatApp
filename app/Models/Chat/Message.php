<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'message_from','conversation_id'];

    public function conversation(){
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}

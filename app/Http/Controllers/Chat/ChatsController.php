<?php

namespace App\Http\Controllers\Chat;

use App\Events\Chat\ChatEvent;
use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat\Conversation;
use App\Models\Chat\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChats()
    {
        $conversations = Conversation::with('messages')->get();
        return $conversations;
    }


    public function sendChat(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $conversations = Conversation::where('user_one', $user->id)
                            ->orWhere('user_two', $user->id)
                            ->first();
        if($conversations == null){
            // no conversation found , create one
            return response()->json(['error' => "Failed to send message. No conversation history found"]);
        }
        else {
            // send message
            $message = new Message(
                [
                    'message' => $request->message,
                    'message_from' => $request->mID,
                    'message_to' => $request->fID,
                ]
            );

            // return response()->json(['error' => "now here", 'other' => $request->cID]);
            // return $message;
            $conversations = Conversation::find($request->cID);

            $conversations->messages()->save($message);
            // We are using the toOthers() which allows us to exclude the current user from the broadcast's recipients.
            broadcast(new TestEvent($user, $message))->toOthers();

            return response()->json(['success' => "sent"]);
        }
        
    }

}

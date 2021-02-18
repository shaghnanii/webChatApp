<?php

namespace App\Http\Controllers;

use App\Events\Chat\ChatEvent;
use App\Models\Chat\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        // DB::enableQueryLog();
        $conversations = Conversation::where('user_one',auth()->user()->id)
                            ->orWhere('user_two',auth()->user()->id)
                            ->with(['messages' => 
                                function($query){
                                    $query->orderBy('id', 'DESC')
                                    ->first();
                                },'u_two','u_one'])

                            ->withCount(['messages as unread_count' => 
                                function($query){
                                    $query->where('is_seen',0);
                                }
                            ])
                            // ->with('messages')
                            ->get();
        // return $conversations;
        return view('chats.chat_view')->with('conversations', $conversations);
    }
}

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
                            ->with(['messages', 'u_two','u_one'])
                            ->get();
        // return $conversations;
        return view('chats.chat_view')->with('conversations', $conversations);
    }
}

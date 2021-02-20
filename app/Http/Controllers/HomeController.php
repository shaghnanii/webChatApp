<?php

namespace App\Http\Controllers;

use App\Events\Chat\ChatEvent;
use App\Models\Chat\Conversation;
use App\Models\Chat\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // DB::enableQueryLog();
        $id = Auth::user()->id;
        $conversations = Conversation::where('user_one',$id)
        ->orWhere('user_two',$id)
        ->with([
                'messages' => function($query){
                    $query->orderBy('id', 'desc')->first();
                },
                'u_one',
                'u_two'
                ])

        ->withCount(['messages as unread_count' => 
            function($query){
                $query->where('is_seen',0);
            }
        ])
        ->get();

        // return $musers = User::with('conversations1', 'conversations2')->get();

        // $msgs = Message::with('conversation')->get();
        // return $msgs;
        // $conversations = Conversation::where('user_one', Auth::user()->id)
        //                                 ->orWhere('user_two', Auth::user()->id)
        //                                 ->with(['messages:id,conversation_id,' 
        //                                                 => function($query) {
        //                                                         $query
        //                                                         ->select('conversation_id', DB::raw('MAX(id) as last_chat_id'))
        //                                                         ->groupBy('conversation_id');
        //                                                     },
        //                                             'u_one', 'u_two'
        //                                         ]
        //                                 )
        //                                 ->withCount(['messages as unread_msg_count'
        //                                                                         => function($query){
        //                                                                             $query->where('is_seen', 0);
        //                                                                         }
        //                                             ]
        //                                 )
        //                                 ->get();

        // dd(DB::getQueryLog());
        // return $conversations;
        return view('chats.chat_view')->with('conversations', $conversations);
    }
}

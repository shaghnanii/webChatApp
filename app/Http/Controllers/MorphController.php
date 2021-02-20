<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity\Comment;
use App\Models\UserActivity\Page;
use App\Models\UserActivity\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MorphController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        $posts = Post::all();
        // return $comment->commentable;
        // return $post->comments;
        return view('morph_view')->with('posts',$posts)->with('pages', $pages);
    }
}

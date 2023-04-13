<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
    /**
     *
     */
    private $limit = 20;

    /**
     *
     */
    public function index()
    {
        //Recupero
        $user = auth()->user();

        $posts = DB::table('posts')->whereIn('user_id', function($query) use ($user){
                                                $query->select('user_id_receive')
                                                        ->from('followers')
                                                        ->where('user_id_send','=', $user->id);
                                            })
                                    ->orderBy('posts.created_at','desc')
                                    ->offset(0)
                                    ->limit($this->limit)
                                    ->get()
                                    ->each(function($post){
                                        $post->likes_count = Post::find($post->id)->likes()->get()->count();
                                        $post->comments_count = Post::find($post->id)->comments()->get()->count();
                                        $post->likes = [];
                                        $post->user = Post::find($post->id)->user()->first();
                                        $post->created_at = Carbon::parse($post->created_at);
                                        $post->updated_at = Carbon::parse($post->updated_at);
                                    });


        return view('timeline', ['posts' => $posts]);
    }
}
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
                                    ->orderBy('posts.updated_at','desc')
                                    ->offset(0)
                                    ->limit($this->limit)
                                    ->get()
                                    ->each(function($post){
                                        //Se verifica si el usuario actual le di like o no al post (Si array es vacio, no le dio like)
                                        $userLikeToPost = DB::table('likes')->select('likes.id')
                                                                            ->where('post_id','=',$post->id)
                                                                            ->where('user_id','=',auth()->user()->id)
                                                                            ->get();
                                        $post->likes_count = Post::find($post->id)->likes()->get()->count();
                                        $post->comments_count = Post::find($post->id)->comments()->get()->count();
                                        $post->likes = $userLikeToPost  ;
                                        $post->user = Post::find($post->id)->user()->first();
                                        $post->created_at = Carbon::parse($post->created_at);
                                        $post->updated_at = Carbon::parse($post->updated_at);

                                    });


        return view('timeline', ['posts' => $posts]);
    }

    /**
     *
     */
    public function postsMoreResults( $page_number)
    {
        //Recupero
        $user = auth()->user();

        $now = \Carbon\Carbon::now();

        $posts = DB::table('posts')->whereIn('user_id', function($query) use ($user){
                                                $query->select('user_id_receive')
                                                        ->from('followers')
                                                        ->where('user_id_send','=', $user->id);
                                            })
                                    ->orderBy('posts.updated_at','desc')
                                    ->offset(($page_number - 1)*$this->limit)
                                    ->limit($this->limit)
                                    ->get()
                                    ->each(function($post) use ($now){
                                        //Se verifica si el usuario actual le di like o no al post
                                        $userLikeToPostArray = DB::table('likes')->select('likes.id')
                                                                            ->where('post_id','=',$post->id)
                                                                            ->where('user_id','=',auth()->user()->id)
                                                                            ->get();

                                        $userLikeToPost = false;
                                        if (count($userLikeToPostArray) != 0) {
                                            $userLikeToPost = true;
                                        }

                                        $post->likes_count = Post::find($post->id)->likes()->get()->count();
                                        $post->comments_count = Post::find($post->id)->comments()->get()->count();
                                        $post->userLikeToPost = $userLikeToPost;
                                        $post->user = Post::find($post->id)->user()->first();

                                        //
                                        if(Carbon::parse($post->created_at)->eq(Carbon::parse($post->updated_at)))  $post->edited = false;
                                        else  $post->edited = true;
                                        //
                                        $post->created_at = Carbon::parse($post->created_at)->toDayDateTimeString();
                                        // $post->updated_at = Carbon::parse($post->updated_at)->toDayDateTimeString();
                                        $post->updated_at =  str_replace('before', 'ago', Carbon::parse($post->updated_at)->diffForHumans($now));
                                        $post->auth_user_profile_image = auth()->user()->profile_image;
                                        $post->user_posts_redirect = route('posts',$post->user->id);

                                    });


        return response()->json([
                                 'state' => true,
                                 'posts' => $posts,
                                ],200);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\get;

class LikesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($post_id)
    {
        // $post_id = $request->id;
        $post = Post::find($post_id);

        $message = '';
        //Verificacion de si el usuario ya dio o no like al post
        if($post->likes()->get()->contains(auth()->user()->id)){
            //Saca like
            $post->likes()->detach(auth()->user()->id);
            $message =  'Like out to post succesfull.';
            $like = false;
        }else{
            //Dar like
            $post->likes()->attach(auth()->user()->id);
            $message =  'Like to post succesfull.';
            $like = true;
        }

        //Response
        return response()->json([
            'state' => true,
            'message' => $message,
            'like' => $like,
            'number_like' => ($post->likes()->count() == 0) ? '' : $post->likes()->count()
        ],200);
    }

    /**
     * Permite listar los usuarios que dieron like a un post
     */
    public function list($post_id)
    {
        $post = Post::find($post_id);

        if (!$post) {
            return response()->json([
                'state' => false,
                'message' => 'Operation fail. Post does not exist.',
                'users' => []
            ],200);
        }

        return response()->json([
            'state' => true,
            'message' => 'Operation succesfull',
            'users' => $post->likes()->get( )->each(function($user){
                //Cargo la url del perfil de cada usuario
                $user['route_posts']  = route('posts',$user->id);
                return $user;
            }),
        ],200);
    }

}
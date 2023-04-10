<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    private $limit = 10;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Post::find($request->post_id);

        if ($post) {
            if (empty($request->content_comment)) {
                return response()->json([
                    'state' => false,
                    'message' => 'Operation fail. Comment empty.',
                ],200);
            }

            if (strlen($request->content_comment) > 100) {
                return response()->json([
                    'state' => false,
                    'message' => 'Operation fail. Comment\'s lenght is more than 100 characters!.',
                ],200);
            }

            if (auth()->user()->id != $post->user_id) {
                $userOwner = User::find($post->user_id);
                if ($userOwner) {
                    if (!$userOwner->followers()->get()->contains(auth()->user()->id)) {
                        return response()->json([
                            'state' => false,
                            'message' => 'Operation fail. User is not follower of author of post',
                        ],200);
                    }
                }
            }

            //Se guarda el comenteario
            $post->comments()->attach(auth()->user()->id, ['content' => $request->content_comment]);

            $comment = $post->comments()
                            ->get()
                            ->last();
            $comment->pivot['created_at_formated'] =  $comment->pivot->created_at->toDayDateTimeString();
            $comment['commentBelongsToCurrentUser'] = $comment->pivot->user_id == auth()->user()->id;

            //
            return response()->json([
                'state' => true,
                'message' => 'Operation successfully.',
                'comment' =>   $comment,
                'amountOfComments' => $post->comments()->count()
            ],200);
        }else{
            return response()->json([
                'state' => false,
                'message' => 'Operation fail.',
            ],200);
        }
    }

    /**
     * Lista los comentarios del post
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $post = Post::find($request->postId);

        if ($post) {
            return response()->json([
                'state' => true,
                'message' => 'Operation succesfull.',
                'comments' => $post->commentsOrderByCreatedDateDescWithLimit(0,$this->limit)
                                    ->get()
                                    ->each(function($comment){
                                        //Se envia un campo con la fecha de creacion del comentario con formato
                                         $comment->pivot['created_at_formated'] =  $comment->pivot->created_at->toDayDateTimeString();
                                         //Se indica si el comentario pertenece o no al usuario actual
                                         $comment['commentBelongsToCurrentUser'] = $comment->pivot->user_id == auth()->user()->id;
                                         return $comment;
                                    }),
                'amountOfComments' => $post->comments()->count(),

            ],200);
        }else{
            return response()->json([
                'state' => false,
                'message' => 'Operation fail.',
                'comments' => []
            ],200);
        }

    }


    /**
     * Lista los comentarios del post
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listMore(Request $request)
    {
        $post = Post::find($request->postId);


        if (! isset($request->page) || (int)$request->page < 1) {
            return response()->json([
                'state' => false,
                'message' => 'Operation fail. Error en number page: it shoulbe > 1',
                'comments' => []
            ],200);
        }

        //Se recupera la pagina a cargar
        $page = (int)$request->page;
        $offset = ($this->limit * ($page - 1));

        if ($post) {
            return response()->json([
                'state' => true,
                'message' => 'Operation succesfull.',
                'comments' => $post->commentsOrderByCreatedDateDescWithLimit($offset,10)
                                    ->get()
                                    ->each(function($comment){
                                        //Se envia un campo con la fecha de creacion del comentario con formato
                                         $comment->pivot['created_at_formated'] =  $comment->pivot->created_at->toDayDateTimeString();
                                         //Se indica si el comentario pertenece o no al usuario actual
                                         $comment['commentBelongsToCurrentUser'] = $comment->pivot->user_id == auth()->user()->id;
                                         return $comment;
                                    }),
                'nextPage' => $page + 1, //Proxima pagina a cargar
            ],200);
        }else{
            return response()->json([
                'state' => false,
                'message' => 'Operation fail.',
                'comments' => []
            ],200);
        }

    }

      /****************************************
     * Remove the specified resource from storage.
     *
     */
    public function destroy(Request $request)
    {
        //Se verifica si se envio el id del comentario.
        if (!isset($request->commentId)) {
            return response()->json([
                'state' => false,
                'message' => 'Operation fail. Comment id no send.',
            ],200);
        }
        //Se recupera id del comentario.
        $comment_id = $request->commentId;
        //Se busca comentario.
        $comment = DB::table('comments')->where('id',$comment_id)->first();
        //Se verifica si existe el comentario.
        if (!$comment) {
            return response()->json([
                'state' => false,
                'message' => 'Operation fail. Comment not found.',
            ],200);
        }
        //Se verifica si el comentario pertenece al usuario
        if ( !($comment->user_id == auth()->user()->id)) {
            return response()->json([
                'state' => false,
                'message' => 'Operation fail. Comment does not belong to the user.',
            ],200);
        }
        //Se elimina el comentario.
        DB::table('comments')->where('id',$comment_id)->delete();
        return response()->json([
            'state' => true,
            'message' => 'Operation succesfull.',
        ],200);
    }

}
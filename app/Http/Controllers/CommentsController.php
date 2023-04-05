<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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

            //Se guarda el comenteario
            $post->comments()->attach(auth()->user()->id, ['content' => $request->content_comment]);

            $comment = $post->comments()
                            ->get()
                            ->last();
            $comment->pivot['created_at_formated'] =  $comment->pivot->created_at->toDayDateTimeString();

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

}

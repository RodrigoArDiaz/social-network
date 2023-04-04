<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
                'comments' => $post->comments()
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
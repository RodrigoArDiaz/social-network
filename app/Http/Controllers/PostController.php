<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private $disk = 'public';
    private $pathPost = 'posts/';
    /****************************************
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id){
        $user = User::find($user_id);
        $isFollowing = false; //Indica si el usuario actual esta siguiendo o no al usuario del post
        $isFollower = false;
        if(auth()->user()->id != $user_id){
            if ($user->followers()->get()->contains(auth()->user()->id )) {
                $isFollowing = true;
            }
            if ($user->followingTo()->get()->contains(auth()->user()->id)) {
                $isFollower = true;
            }
        }
        return view('posts',['posts' => $user->posts()->orderBy('created_at','desc')->get(), 'user' => $user, 'isFollowing' => $isFollowing, 'isFollower' => $isFollower]);
        // return view('posts',['posts' => $user->posts()->orderBy('created_at','desc')->get()->load('comments'), 'user' => $user]);
    }

    /********************************************
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /********************************************
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Obtengo usuario autenticado;
         $user = auth()->user();

        //Recuperacion de imagen
        $image = $request->file('image');

        //Validaciones aqui
        if (empty($request->content)) {
            //Post no debe estar vacio
            $message_error = "Post can't be empty!";
            session()->flash('error', true);
            session()->flash('message_error', $message_error);
        }elseif (strlen($request->content) > 300) {
            //Post no debe superar los 300 caracteres
            $message_error = "Post's lenght is more than 300 characters!";
            session()->flash('error', true);
            session()->flash('message_error', $message_error);
        }else {
            $hasImage = false;
            if (isset($image)) {
                //Si se envio el archivo, se verifica que el tipo sea imagen
                if (strpos($image->getMimeType(), 'image') ===  false) {
                    $message_error = "File type not allowed. Only images are allowed (png, jpg, ico)";
                    session()->flash('error', true);
                    session()->flash('message_error', $message_error);
                    return redirect()->route('posts',$user->id);
                }else $hasImage = true;
            }

            if($hasImage) {
                //Creo nuevo post
                $post = new Post(['content' => $request->content, 'user_id' => $user->id]);
                $post->save();
                //Guardo archivo
                $name = str_replace(' ','_',$user->name ).'_'.$user->id.'_post_'.$post->id;
                $name_with_extension = $image->storeAs($this->pathPost, $name.".".$image->extension(), $this->disk);
                //Se obiene el path de la imagen
                $path_image =asset(Storage::disk($this->disk)->url($name_with_extension));
                //Se guarda imagen en el post
                $post->image = $path_image;
                $post->save(); //Guardo datos sin actualizar fecha
            }else{
                $post = new Post(['content' => $request->content, 'user_id' => $user->id]);
                $post->save();
            }
            //Guardo datos
            session()->flash('error', false);
            session()->flash('message_error', '');
        }
         return redirect()->route('posts',$user->id);
    }

    /**************************************
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /****************************************
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.post-edit',['post' => $post, 'user' => auth()->user()]);
    }

    /****************************************
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //Obtengo usuario autenticado;
        $user = auth()->user();

        //Recuperacion de imagen
        $image = $request->file('image_edit');

        //Validaciones aqui
        if (empty($request->content_edit)) {
            //Post no debe estar vacio
            $message_error = "Post can't be empty!";
            session()->flash('error', true);
            session()->flash('message_error', $message_error);
            return redirect()->route('post.edit',$post->id);
        }elseif (strlen($request->content_edit) > 300) {
            //Post no debe superar los 300 caracteres
            $message_error = "Post's lenght is more than 300 characters!";
            session()->flash('error', true);
            session()->flash('message_error', $message_error);
            return redirect()->route('post.edit',$post->id);
        }else {

            //Indica si se tiene que eliminar la imagen anterior (sin importar si se envio o no un nuevo archivo para guardar)
            if ($request->boolean('no-image')) {
                //Elimino anterior foto
                if(!empty($post->image)){
                    $array = explode('/',$post->image);
                    $name_old = $array[array_key_last($array)];
                    Storage::disk($this->disk)->delete($this->pathPost.$name_old); //Borrado
                    //Borro ruta
                    $post->image = '';
                }
            }

            //Revisa si se envio o no un nuevo archivo de imagen
            $hasImage = false;
            if (isset($image)) {
                //Si se envio el archivo, se verifica que el tipo sea imagen
                if (strpos($image->getMimeType(), 'image') ===  false) {
                    $message_error = "File type not allowed. Only images are allowed (png, jpg, ico)";
                    session()->flash('error', true);
                    session()->flash('message_error', $message_error);
                    return redirect()->route('post.edit',$post->id);
                }else $hasImage = true;
            }

            if($hasImage) {
                //Elimino anterior fotos
                $array = explode('/',$post->image);
                $name_old = $array[array_key_last($array)];
                Storage::disk($this->disk)->delete($this->pathPost.$name_old); //Borrado

                //Guardo archivo
                $name = str_replace(' ','_',$user->name ).'_'.$user->id.'_post_'.$post->id;
                $name_with_extension = $image->storeAs($this->pathPost, $name.".".$image->extension(), $this->disk);
                //Se obiene el path de la imagen
                $path_image =asset(Storage::disk($this->disk)->url($name_with_extension));
                //Se guarda imagen en el post
                $post->image = $path_image;
                $post->content = $request->content_edit;
                $post->save(); //Guardo datos
                $post->touch(); //Para actualizar updated_at
            }else{
                $post->content = $request->content_edit;
                $post->save(); //Guardo datos
                $post->touch(); //Para actualizar updated_at
            }

            session()->flash('error', false);
            session()->flash('message_error', '');
        }
         return redirect()->route('posts',$user->id);


    }

    /****************************************
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //Usuario al cual pertenece el post
        $user_id = $post->user_id;
        //Usuario del post debe ser igual al que hace la peticion
        if($user_id !== auth()->user()->id){
            return 'Error';
        }
        // Comment::where('post_id', $post->id)->delete();
        //Elimino  foto del post
        $array = explode('/',$post->image);
        $name_old = $array[array_key_last($array)];
        Storage::disk($this->disk)->delete($this->pathPost.$name_old); //Borrado


        //Se elimina el post
        $post->delete();
        // // return to_route('posts');
        return redirect()->route('posts',auth()->user()->id);
    }
}
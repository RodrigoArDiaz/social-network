<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ConnectsController extends Controller
{
    private $offset = 0;
    private $limit = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('connect');
    }

    /*********
     * Search user
     */
    public function search(Request $request)
    {
        //Reseteo a valor inicial
        session(['userSearchOffset' => $this->offset]);

        //Verificaciones
        if (empty($request->name)) {
            return view('connect',[ 'state_search' => false, 'users'=> [], 'search_key' => '' ]);
        }

        //Guardo criterio de busqueda
        session(['userNameSearch' => $request->name]);

        //usuarios que cumplan el criterio, no se carga el usuario actual
        $users =   User::where('name','LIKE',"%{$request->name}%")
                        ->where('id','!=',auth()->user()->id)
                        ->orderBy('name', 'ASC')
                        ->offset($this->offset)
                        ->limit($this->limit)
                        ->get()
                        ->load([ 'followers' => fn ($query) => $query->where('user_id_send', auth()->user()->id) ]);

        return view('connect',[ 'state_search' => true, 'users'=> $users, 'search_key' => $request->name ]);
    }

     /**********
     * Search user, more results
     */
    public function searchMoreResults()
    {
        //Recupero valor de busqueda
        $name = session('userNameSearch');
        //Recupero offset y aumento
        $offset =   session('userSearchOffset');
        $offset = $offset + $this->limit;
        session(['userSearchOffset' => $offset]);

        //usuarios que cumplan el criterio, no se carga el usuario actual
        $users =   User::where('name','LIKE',"%{$name}%")
                        ->where('id','!=',auth()->user()->id)
                        ->orderBy('name', 'ASC')
                        ->offset($offset)
                        ->limit($this->limit)
                        ->get()
                        ->load([ 'followers' => fn ($query) => $query->where('user_id_send', auth()->user()->id) ]);
        //Se retorna json
        return response()->json([
            'state' => true,
            'users' => $users,
            'offset' => $offset,
            'limit' => $this->limit
        ],200);
    }

    /**
     *  Realiza la conexion entre dos usuarios (follow)
     */
     public function follow(Request $request)
     {
        $user_id_send = auth()->user()->id;
        $user_id_receive = $request->user_id_follow;
        //Comprueba si ya se envio la solicitud o no
        if(User::find($user_id_send)->followingTo()->get()->contains($user_id_receive)){
            return response()->json([
                'state' => false,
                'message' => 'This user is already being followed.'
            ],200);
        }else {
            User::find($user_id_send)->followingTo()->attach($user_id_receive);

            if (User::find($user_id_receive)->followingTo()->get()->contains($user_id_send))  $connected  = true;
            else $connected = false;
            // $connected = User::find($user_id_receive)->followingTo()->get()->contains($user_id_send) ? true : false;
            return response()->json([
                'state' => true,
                'message' => 'Follow-up request sent successfully.',
                'isConnected' =>  $connected
            ],200);
        }
     }

     /**
     *  Realiza la desconexion entre dos usuarios (unfollow)
     */
    public function unfollow(Request $request)
    {
       $user_id_send = auth()->user()->id;
       $user_id_receive = $request->user_id_unfollow;
       //Comprueba si ya se dejo de seguir al usuario
       if(User::find($user_id_send)->followingTo()->get()->contains($user_id_receive)){
            User::find($user_id_send)->followingTo()->detach($user_id_receive); //deja de seguir
            return response()->json([
                'state' => true,
                'message' => 'Unfollowed succesfully.'
            ],200);
       }else {
           return response()->json([
               'state' => false,
               'message' => 'This user is already being unfollowed.',
           ],200);
       }
    }



}

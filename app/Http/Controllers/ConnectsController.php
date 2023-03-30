<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ConnectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('connect');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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


    /**
     * Search user
     */
    public function search(Request $request)
    {
        //Verificaciones
        if (empty($request->name)) {
            return view('connect',[ 'state_search' => false, 'users'=> [], 'search_key' => '' ]);
        }

        //usuarios que cumplan el criterio, no se carga el usuario actual
        // $users =   User::where('name','LIKE',"%{$request->name}%")->where('id','!=',auth()->user()->id)->get();
        $users =   User::where('name','LIKE',"%{$request->name}%")->where('id','!=',auth()->user()->id)->get()->load([ 'followers' => fn ($query) => $query->where('user_id_send', auth()->user()->id) ]);

        return view('connect',[ 'state_search' => true, 'users'=> $users, 'search_key' => $request->name ]);
    }

    /**
     *  Realiza la conexion entre dos usuarios
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
     *  Realiza la desconexion entre dos usuarios
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
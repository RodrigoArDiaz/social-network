<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ConnectionsTrait;
use Illuminate\Http\Request;

class ConnectsController extends Controller
{
    //Uso de trait
    use ConnectionsTrait;
    //Variables privadas
    private $offset = 0;
    private $limit = 20;

    /**********************************************************
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('connect');
    }

    /***********************************************************
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

     /**********************************************************
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

    /**********************************************************
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

     /***********************************************************
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

    /************************************************************
     * Retorna las conexiones del usuario partir de pagina 1. Redirige a vista
     */
    public function connections($user_id)
    {
        //Recupero usuario
        $user = User::find($user_id);

        //Verificacion de la existencia del usuario
        if (!$user) {
            return response()->json([
                'state' => false,
                 'message' => 'User not found.'
            ],200);
        }

        //Uso de la funcion del trait
        $userInformationConnections  = $this->userInformationConnections($user_id);

        //Recuperacion de pagina 1 de lista de conexiones
        $connections = $this->getConnectionWithPagination($user,1, $this->limit);

        //Retorno de json
        return view('connections', ['user' => $user,
                                    // 'isFollowing' => $isFollowing,
                                    // 'isFollower' => $isFollower,
                                    'numberOfFollowers' => $userInformationConnections['numberOfFollowers'],
                                    'numberOfFollowing' => $userInformationConnections['numberOfFollowing'],
                                    'numberOfConnections' => $userInformationConnections['numberOfConnections'],
                                    'numberOfPosts' => $userInformationConnections['numberOfPosts'],
                                    'users' => $connections
                                ]);
    }


     /************************************************************
     * Retorna las conexiones del usuarioa partir de pagina 2. Retorna respuesta http en json.
     */
    public function connectionsMoreResults($user_id, $page_number)
    {
        //Recuperacion del usuario
        $user = User::find($user_id);

        //Verificacion de la existencia del usuario
        if (!$user) {
            return response()->json([
                'state' => false,
                 'message' => 'User not found.'
            ],200);
        }

        //Verificacion del numero de pagina
        if (!$this->isPageNumberValid($page_number)) {
            return response()->json([
                'state' => false,
                 'message' => 'Page number is invalid.'
            ],200);
        }

        //Recuperacion de pagina $page_number de lista de conexiones
        $connections = $this->getConnectionWithPagination($user,$page_number, $this->limit);

        //Retorno de json
        return response()->json([
                                'state' => true,
                                'user' => $user,
                                'users' => $connections,
                                'type' => 'connections',
                                'isUserPost' => $user_id == auth()->user()->id,
                            ],200);
    }

    /************************************************************
     * Retorna los seguidores del usuario  partir de pagina 1. Redirige a vista
     */
    public function followers($user_id)
    {
        //Recupero el usuario
        $user = User::find($user_id);

         //Verificacion de la existencia del usuario
         if (!$user) {
            return response()->json([
                'state' => false,
                 'message' => 'User not found.'
            ],200);
        }

        //Uso de la funcion del trait
        $userInformationConnections  = $this->userInformationConnections($user_id);

        //Recuperacion de pagina 1 de lista de seguidore
        $followers = $this->getFollowersWithPagination($user,1, $this->limit);

        return view('connections', ['user' => $user,
                                    'numberOfFollowers' => $userInformationConnections['numberOfFollowers'],
                                    'numberOfFollowing' => $userInformationConnections['numberOfFollowing'],
                                    'numberOfConnections' => $userInformationConnections['numberOfConnections'],
                                    'numberOfPosts' => $userInformationConnections['numberOfPosts'],
                                    'users' => $followers,

                                ]);
    }

     /************************************************************
     * Retorna los seguidores del usuario a partir de pagina 2. Retorna respuesta http en json.
     */
    public function followersMoreResults($user_id, $page_number)
    {
        //Recupero usuario
        $user = User::find($user_id);

        //Verificacion de la existencia del usuario
        if (!$user) {
            return response()->json([
                'state' => false,
                 'message' => 'User not found.'
            ],200);
        }

        //Verificacion del numero de pagina
        if (!$this->isPageNumberValid($page_number)) {
            return response()->json([
                'state' => false,
                 'message' => 'Page number is invalid.'
            ],200);
        }

        //Recuperacion de pagina n de lista de seguidore
        $followers = $this->getFollowersWithPagination($user,$page_number, $this->limit);

        return response()->json([
                          'state' => true,
                          'user' => $user,
                          'users' => $followers,
                          'type' => 'followers',
                          'isUserPost' => $user_id == auth()->user()->id
                      ],200);

    }

    /************************************************************
     * Retorna los usuario que se estan siguiendo del usuario  partir de pagina 1. Redirige a vista
     */
    public function following($user_id)
    {
        $user = User::find($user_id);

         //Verificacion de la existencia del usuario
         if (!$user) {
            return response()->json([
                'state' => false,
                 'message' => 'User not found.'
            ],200);
        }

        //Uso de la funcion del trait
        $userInformationConnections  = $this->userInformationConnections($user_id);

        //Recuperacion de pagina 1 de lista de usuarios seguidos
        $following = $this->getFollowingWithPagination($user, 1, $this->limit);

        //Retorno json
        return view('connections', ['user' => $user,
                                    'numberOfFollowers' => $userInformationConnections['numberOfFollowers'],
                                    'numberOfFollowing' => $userInformationConnections['numberOfFollowing'],
                                    'numberOfConnections' => $userInformationConnections['numberOfConnections'],
                                    'numberOfPosts' => $userInformationConnections['numberOfPosts'],
                                    'users' => $following,
                                ]);
    }

    /***********************************************************
     * Retorna los usuarios que se estan siguiendo del usuario a partir de la pagina 2. Retorna respuesta http en json.
     */
    public function followingMoreResults($user_id, $page_number)
    {
        //Recuperacion de usuario
        $user = User::find($user_id);

        //Verificacion de la existencia del usuario
        if (!$user) {
            return response()->json([
                'state' => false,
                 'message' => 'User not found.'
            ],200);
        }

        //Verificacion del numero de pagina
        if (!$this->isPageNumberValid($page_number)) {
            return response()->json([
                'state' => false,
                 'message' => 'Page number is invalid.'
            ],200);
        }

        //Recuperacion de pagina 1 de lista de usuarios seguidos
        $following = $this->getFollowingWithPagination($user, $page_number, $this->limit);

        //Retorno json
        return response()->json([
                                'state' => true,
                                'user' => $user,
                                'users' => $following,
                                'type' => 'following',
                                'isUserPost' => $user_id == auth()->user()->id
                                ],200);
    }




}
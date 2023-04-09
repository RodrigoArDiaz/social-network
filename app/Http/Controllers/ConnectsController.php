<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ConnectsController extends Controller
{
    private $offset = 0;
    private $limit = 20;
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

    /***
     * Retorna las conexiones del usuario
     */
    public function connections($user_id)
    {
        $user = User::find($user_id);
        //Se obtiene el numero de followers, followings y connects
        $numberOfFollowers = $user->followers()->count();
        $numberOfFollowing = $user->followingTo()->count();
        $numberOfPosts = $user->posts()->count();

        $numberOfConnections = 0;
        //Se cuenta la cantidad de seguidos que tambien siguen al usuario
        $numberOfConnections = $user->followingTo()->whereIn('user_id_receive',function($query) use ($user){
                                                                                    $query->select('user_id_send')
                                                                                            ->from('followers')
                                                                                            ->where('user_id_receive','=', $user->id);
                                                                                }
                                                                                )->count();

        $connections = $user->followingTo()
                            ->whereIn('user_id_receive',function($query) use ($user){
                                                                            $query->select('user_id_send')
                                                                                    ->from('followers')
                                                                                    ->where('user_id_receive','=', $user->id);
                                                                        })
                            ->orderBy('users.name','asc')
                            ->offset(0)
                            ->limit($this->limit)
                            ->get();


        return view('connections', ['user' => $user,
                                    // 'isFollowing' => $isFollowing,
                                    // 'isFollower' => $isFollower,
                                    'numberOfFollowers' => $numberOfFollowers,
                                    'numberOfFollowing' => $numberOfFollowing,
                                    'numberOfConnections' => $numberOfConnections,
                                    'numberOfPosts' => $numberOfPosts,
                                    'users' => $connections
                                ]);
    }


     /***
     * Retorna las conexiones del usuario
     */
    public function connectionsMoreResults($user_id, $page_number)
    {
        $user = User::find($user_id);
        //Se obtiene el numero de followers, followings y connects
        $numberOfFollowers = $user->followers()->count();
        $numberOfFollowing = $user->followingTo()->count();
        $numberOfPosts = $user->posts()->count();

        $numberOfConnections = 0;
        //Se cuenta la cantidad de seguidos que tambien siguen al usuario
        $numberOfConnections = $user->followingTo()->whereIn('user_id_receive',function($query) use ($user){
                                                                                    $query->select('user_id_send')
                                                                                            ->from('followers')
                                                                                            ->where('user_id_receive','=', $user->id);
                                                                                }
                                                                                )->count();

        $connections = $user->followingTo()
                            ->whereIn('user_id_receive',function($query) use ($user){
                                                                            $query->select('user_id_send')
                                                                                    ->from('followers')
                                                                                    ->where('user_id_receive','=', $user->id);
                                                                        })
                            ->orderBy('users.name','asc')
                            ->offset(($page_number - 1)*$this->limit)
                            ->limit($this->limit)
                            ->get();


        return response()->json([
                                'state' => true,
                                'user' => $user,
                                'numberOfFollowers' => $numberOfFollowers,
                                'numberOfFollowing' => $numberOfFollowing,
                                'numberOfConnections' => $numberOfConnections,
                                'numberOfPosts' => $numberOfPosts,
                                'users' => $connections,
                                'type' => 'connections',
                                'isUserPost' => $user_id == auth()->user()->id,
                                'id_recibido' => $user_id,
                                'id_auth' =>  auth()->user()->id,
                            ],200);
    }



    /***
     * Retorna los seguidores del usuario
     */
    public function followers($user_id)
    {

        $user = User::find($user_id);
        //Se obtiene el numero de followers, followings y connects
        $numberOfFollowers = $user->followers()->count();
        $numberOfFollowing = $user->followingTo()->count();
        $numberOfPosts = $user->posts()->count();

        $numberOfConnections = 0;
            //Se cuenta la cantidad de seguidos que tambien siguen al usuario
        $numberOfConnections = $user->followingTo()->whereIn('user_id_receive',function($query) use ($user){
                                                                                    $query->select('user_id_send')
                                                                                            ->from('followers')
                                                                                            ->where('user_id_receive','=', $user->id);
                                                                                }
                                                                        )->count();

        //
        $followers = $user->followersOrderByNameAscWithLimit(0,$this->limit)
                          ->get()
                          ->each(function($follower) use ($user){
                               $follower['following'] = $user->followingTo()->get()->contains($follower->id);
                          });
        return view('connections', ['user' => $user,
                                    // 'isFollowing' => $isFollowing,
                                    // 'isFollower' => $isFollower,
                                    'numberOfFollowers' => $numberOfFollowers,
                                    'numberOfFollowing' => $numberOfFollowing,
                                    'numberOfConnections' => $numberOfConnections,
                                    'numberOfPosts' => $numberOfPosts,
                                    'users' => $followers,

                                ]);
    }

     /***
     * Retorna los seguidores del usuario
     */
    public function followersMoreResults($user_id, $page_number)
    {
        $user = User::find($user_id);
        //Se obtiene el numero de followers, followings y connects
        $numberOfFollowers = $user->followers()->count();
        $numberOfFollowing = $user->followingTo()->count();
        $numberOfPosts = $user->posts()->count();

        $numberOfConnections = 0;
            //Se cuenta la cantidad de seguidos que tambien siguen al usuario
        $numberOfConnections = $user->followingTo()->whereIn('user_id_receive',function($query) use ($user){
                                                                                    $query->select('user_id_send')
                                                                                            ->from('followers')
                                                                                            ->where('user_id_receive','=', $user->id);
                                                                                }
                                                                        )->count();

        //
        $followers = $user->followersOrderByNameAscWithLimit(($page_number - 1)*$this->limit,$this->limit)
                          ->get()
                          ->each(function($follower) use ($user){
                               $follower['following'] = $user->followingTo()->get()->contains($follower->id);
                          });

        return response()->json([
                          'state' => true,
                          'user' => $user,
                          // 'isFollowing' => $isFollowing,
                          // 'isFollower' => $isFollower,
                          'numberOfFollowers' => $numberOfFollowers,
                          'numberOfFollowing' => $numberOfFollowing,
                          'numberOfConnections' => $numberOfConnections,
                          'numberOfPosts' => $numberOfPosts,
                          'users' => $followers,
                          'type' => 'followers',
                          'isUserPost' => $user_id == auth()->user()->id
                      ],200);

    }

    /***
     * Retorna los usuario que se estan siguiendo del usuario
     */
    public function following($user_id)
    {
        $user = User::find($user_id);
        //Se obtiene el numero de followers, followings y connects
        $numberOfFollowers = $user->followers()->count();
        $numberOfFollowing = $user->followingTo()->count();
        $numberOfPosts = $user->posts()->count();

        $numberOfConnections = 0;
            //Se cuenta la cantidad de seguidos que tambien siguen al usuario
        $numberOfConnections = $user->followingTo()->whereIn('user_id_receive',function($query) use ($user){
                                                                                    $query->select('user_id_send')
                                                                                            ->from('followers')
                                                                                            ->where('user_id_receive','=', $user->id);
                                                                                }
                                                                        )->count();

        $following = $user->followingToOrderByNameAscWithLimit(0,$this->limit)->get();
        return view('connections', ['user' => $user,
                                    // 'isFollowing' => $isFollowing,
                                    // 'isFollower' => $isFollower,
                                    'numberOfFollowers' => $numberOfFollowers,
                                    'numberOfFollowing' => $numberOfFollowing,
                                    'numberOfConnections' => $numberOfConnections,
                                    'numberOfPosts' => $numberOfPosts,
                                    'users' => $following,

                                ]);
    }


    public function followingMoreResults($user_id, $page_number)
    {
        $user = User::find($user_id);
        //Se obtiene el numero de followers, followings y connects
        $numberOfFollowers = $user->followers()->count();
        $numberOfFollowing = $user->followingTo()->count();
        $numberOfPosts = $user->posts()->count();

        $numberOfConnections = 0;
            //Se cuenta la cantidad de seguidos que tambien siguen al usuario
        $numberOfConnections = $user->followingTo()->whereIn('user_id_receive',function($query) use ($user){
                                                                                    $query->select('user_id_send')
                                                                                            ->from('followers')
                                                                                            ->where('user_id_receive','=', $user->id);
                                                                                }
                                                                        )->count();

        $following = $user->followingToOrderByNameAscWithLimit(($page_number - 1)*$this->limit,$this->limit)->get();

                                return response()->json([
                                    'state' => true,
                                    'user' => $user,
                                    'numberOfFollowers' => $numberOfFollowers,
                                    'numberOfFollowing' => $numberOfFollowing,
                                    'numberOfConnections' => $numberOfConnections,
                                    'numberOfPosts' => $numberOfPosts,
                                    'users' => $following,
                                    'type' => 'following',
                                    'isUserPost' => $user_id == auth()->user()->id
                                ],200);
    }




}

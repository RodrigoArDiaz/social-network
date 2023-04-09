<?php

namespace App\Traits;

use App\Models\User;


trait ConnectionsTrait {

    /****************************************************
     * Retorna el numero de seguidores, el numero de conecciones y el numero de siguiendo
     * de un usuario dado por el id
     */
    public function userInformationConnections($user_id) {
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
        return ['numberOfFollowers' => $numberOfFollowers,
                'numberOfFollowing' => $numberOfFollowing,
                'numberOfPosts' => $numberOfPosts,
                'numberOfConnections' => $numberOfConnections];

    }

    /****************************************************
     * Retorna una pagina de la lista de conexiones de un usuario
     */

    public function getConnectionWithPagination(User $user, $page_number, $limit)
    {
        return $user->followingTo()
                    ->whereIn('user_id_receive',function($query) use ($user){
                                                                    $query->select('user_id_send')
                                                                            ->from('followers')
                                                                            ->where('user_id_receive','=', $user->id);
                                                                })
                    ->orderBy('users.name','asc')
                    ->offset(($page_number - 1)*$limit)
                    ->limit($limit)
                    ->get();
    }

    /******************************************************
     * Retorna una pagina de la lista de seguidores
     */
    public function getFollowersWithPagination(User $user, $page_number, $limit)
    {
        return $user->followersOrderByNameAscWithLimit(($page_number - 1)*$limit,$limit)
                    ->get()
                    ->each(function($follower) use ($user){
                        $follower['following'] = $user->followingTo()->get()->contains($follower->id);
                    });
    }

    /******************************************************
     * Retorna una pagina de la lista de usuarios seguis
     */
    public function getFollowingWithPagination(User $user, $page_number, $limit)
    {
        return $user->followingToOrderByNameAscWithLimit(($page_number - 1)*$limit,$limit)->get();
    }

    /*********************************************
     * Verificacion de pagina
     */
    public function isPageNumberValid($page_number)
    {
        $isValid = true;
        $page_number = intval($page_number);
        if (empty($page_number)) $isValid =  false;
        if ($page_number < 0)  $isValid =  false;

       return $isValid;
    }



}

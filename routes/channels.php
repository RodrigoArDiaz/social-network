<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


/**
 * Ruta del canal sobre cual cada usuario debe escuchar, solo puede escuchar el usuario dueño de ese channel
 */
Broadcast::channel('notification.{user_id}', function($user, $user_id) {
    //Si el canal de notificacion es el del usuario, se puede escuchar en el
    if ($user->id == $user_id) {
        return $user;
    }
});

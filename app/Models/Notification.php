<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    /**
     * type:
     *
     *  PL = post like, user_id_send likeo un post del usuario, post es indicado con post_id
     *  PC = post comment, user_id_send comento el post del usuario,  post es indicado con post_id
     *  UF = user follow, user_id_send comenzo a seguir al usuario,
     *  UC = user connect, usuarios estan conectados*
     */

    protected $fillable = [
        'type',
        'user_id_receive',
        'user_id_send',
        'post_id',
    ];

    //Usuario propietario de la notificacion
    public function userReceive()
    {
        return $this->belongsTo(User::class, 'id', 'user_id_receive');
    }

    //Usuario que envia la notificacion
    public function userSend()
    {
        return $this->belongsTo(User::class, 'id', 'user_id_send');
    }

    //Post
    public function post()
    {
        return $this->belongsTo(Post::class, 'id', 'post_id');
    }
}
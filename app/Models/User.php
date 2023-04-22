<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Relacion 1 a N entre tabla Users y tabla Posts
     */
    public function posts(){
        return $this->hasMany(Post::class);
    }


    /**
     * Auto relacion N a N , usuarios que sigue
     */
    public function followingTo()
    {
        return $this->belongsToMany(User::class,'followers', 'user_id_send', 'user_id_receive')->withTimestamps();

    }


    /**
     * Auto relacion N a N , usuarios que siguen al usuario
     */
    public function followers()
    {
        return $this->belongsToMany(User::class,'followers', 'user_id_receive', 'user_id_send')->withTimestamps();

    }

    /**
     * Relacion N a N entre tabla Users y tabla  Posts
     */
    public function likes(){
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    /**
     * Relacion N a N entre tabla Users y tabla  Posts
     */
    public function comments(){
        return $this->belongsToMany(Post::class, 'comments')->withPivot(['content', 'id'])->withTimestamps();
    }

    /**
     * Auto relacion N a N , usuarios que siguen al usuario ordenado por name y con paginacion
     */
    public function followersOrderByNameAscWithLimit($offset, $limit)
    {
        return $this->belongsToMany(User::class,'followers', 'user_id_receive', 'user_id_send')
                    ->withTimestamps()
                    ->orderBy('users.name','asc')
                    ->offset($offset)
                    ->limit($limit);

    }

    /**
     *  Auto relacion N a N , usuarios que el usuario sigue, ordenados por name y con paginacion
     */
    public function followingToOrderByNameAscWithLimit($offset,$limit)
    {
        return $this->belongsToMany(User::class,'followers', 'user_id_send', 'user_id_receive')
                    ->withTimestamps()
                    ->orderBy('users.name','asc')
                    ->offset($offset)
                    ->limit($limit);
    }


    /**
     *  Auto relacion N a N , usuarios que el usuario sigue, ordenados por created_ad y con limite
     */
    public function followersOrderByCreatedAtDescWithLimit($offset, $limit)
    {
        return $this->belongsToMany(User::class,'followers', 'user_id_receive', 'user_id_send')
                    ->withTimestamps()
                    ->orderByPivot('created_at','desc')
                    ->offset($offset)
                    ->limit($limit);
    }

    /**
     *  Auto relacion N a N , usuarios que el usuario sigue, ordenados por created_ad y con paginacion
     */
    public function followingToOrderByCreatedAtDesWithLimit($offset,$limit)
    {
        return $this->belongsToMany(User::class,'followers', 'user_id_send', 'user_id_receive')
                    ->withTimestamps()
                    ->orderByPivot('created_at','desc')
                    ->offset($offset)
                    ->limit($limit);
    }

    /**
     * Auto relacion N a N , notificaciones del usuario
     */
    // public function noticationsReceive()
    // {
    //     return $this->belongsToMany(User::class,'notifications', 'user_id_receive', 'user_id_send')
    //                 ->withPivot(['id', 'type', 'post_id'])
    //                 ->withTimestamps()
    //                 ->select('users.id','users.name', 'users.profile_image');
    // }


    /**
     * Auto relacion N a N , notificaciones recibidas del usuario (leidas y no leidas)
     */
    public function noticationsReceive()
    {
        return $this->hasMany(Notification::class, 'user_id_receive')
                    ->orderBy('notifications.created_at', 'desc'); //uso has many para obtener solo informacion de la notificacion
    }

    /**
     *
     */
    public function noticationsReceiveWithLimit($offset, $limit)
    {
        return $this->hasMany(Notification::class, 'user_id_receive')
                    ->orderBy('notifications.created_at', 'desc')
                    ->offset($offset)
                    ->limit($limit); //uso has many para obtener solo informacion de la notificacion
    }

    /**
     *  Notificaciones del usuario que no fueron leidas
     */
    public function noticationsReceiveUnread()
    {
        return $this->hasMany(Notification::class, 'user_id_receive')
                    ->where('notifications.state', '=', 'U')
                    ->orderBy('notifications.created_at', 'desc'); //uso has many para obtener solo informacion de la notificacion
    }

    /**
     *  Notificaciones del usuario que no fueron leidas
     */
    public function noticationsReceiveUnreadWithLimit($offset, $limit)
    {
        return $this->hasMany(Notification::class, 'user_id_receive')
                    ->where('notifications.state', '=', 'U')
                    ->orderBy('notifications.created_at', 'desc')
                    ->offset($offset)
                    ->limit($limit); //uso has many para obtener solo informacion de la notificacion
    }

}
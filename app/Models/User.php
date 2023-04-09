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
}

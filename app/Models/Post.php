<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    //Campos que se pueden insertar
    protected $fillable = [
        'content',
        'image',
        'user_id',
    ];


    /**
     * Relacion N a 1 entre tabla Post y tabla Users
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Relacion N a N entre tabla Posts y Tabla Users
     */
    public function likes(){
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

     /**
     * Relacion N a N entre tabla Posts y Tabla Users
     */
    public function comments(){
        return $this->belongsToMany(User::class, 'comments')->withPivot('content')->withTimestamps();
    }



}
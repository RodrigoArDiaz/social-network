<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use PhpParser\Node\Expr\New_;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Crea n usuarios
        // \App\Models\User::factory(5)->create();

        // //Crea n usuarios, y hacen seguidores de usuario de id $user_id
        // \App\Models\User::factory(13)->create()->each(function($user){
        //     $user_id = 1;
        //     $user->followingTo()->attach($user_id);
        // });

        // //Crea n usuarios, y hace que el usurio de id $user_id los siga
        // \App\Models\User::factory(13)->create()->each(function($user){
        //     $user_id = 1;
        //     User::find($user_id)->followingTo()->attach($user->id);
        // });

        // //Crea n usuarios, y hace se sigan mutuamente con el usuario de id $user_id
        // \App\Models\User::factory(13)->create()->each(function($user){
        //     $user_id = 1;
        //     $user->followingTo()->attach($user_id);
        //     User::find($user_id)->followingTo()->attach($user->id);
        // });

        // //Crea n usuarios, y hace se sigan mutuamente con el usuario de id $user_id
        // \App\Models\User::factory(1)->create()->each(function($user){
        //     $post = new Post();

        //     $user_id = 1;
        //     $user->followingTo()->attach($user_id);
        //     User::find($user_id)->followingTo()->attach($user->id);
        // });

        //Crea n usuarios, con 15 posts cada uno
        // \App\Models\User::factory(10)
        // ->has(Post::factory()->count(15), 'posts')
        // ->create();

        //Crea n usuarios, con una cantidad aleatoria de posts
        //    \App\Models\User::factory(5)->create()->each(function($user){
        //         Post::factory()->count(rand(0,5))->for($user)->create();
        //     });


        //Crea n usuarios, cada usuario sigue a una cantidad aleatoria de otros usuarios y se crean una cantidad aleatoria de cada post
        // $userToCreate = 100;
        // \App\Models\User::factory($userToCreate)->create()->each(function($user){ //Nota se crea primero los n usuarios y se devuelve una coleccion con create. Despues se recorre con each esa coleccion de n usuario
        //     $numbersOfUser = User::all()->count();

        //     //Selecciona usuarios aleatorios para seguir
        //     $numbersOfUserToFollow = rand(0,$numbersOfUser);
        //     $usersToFollow = User::inRandomOrder()->where('id', '!=', $user->id)->limit($numbersOfUserToFollow)->get();
        //     foreach ($usersToFollow as $userToFollow) {
        //         $user->followingTo()->attach($userToFollow->id);
        //     }

        //     //Crea posts aleatorio para el usuario
        //     $numberOfPosts = rand(0,10);
        //     Post::factory()->count($numberOfPosts)->for($user)->create();
        // });

        $userToCreate = 100;
        \App\Models\User::factory($userToCreate)->create()->each(function($user){ //Nota se crea primero los n usuarios y se devuelve una coleccion con create. Despues se recorre con each esa coleccion de n usuario
            $numbersOfUser = User::all()->count();

            //Selecciona usuarios aleatorios para seguir
            $numbersOfUserToFollow = rand(0,$numbersOfUser);
            $usersToFollow = User::inRandomOrder()->where('id', '!=', $user->id)->limit($numbersOfUserToFollow)->get();
            foreach ($usersToFollow as $userToFollow) {
                $user->followingTo()->attach($userToFollow->id);
            }

            //Crea posts aleatorio para el usuario
            $numberOfPosts = rand(0,10);
            Post::factory()->count($numberOfPosts)->for($user)->create();

            //Crea likes a post
            $numberOfPost = Post::all()->count();
            $numbersOfPostToLike = rand(0,$numberOfPost);
            $postToLike = Post::inRandomOrder()->limit($numbersOfPostToLike)->get();
            foreach ($postToLike as $post) {
                $post->likes()->attach($user->id);
            }

            //Crea comment a posts
            $numberOfPostToComment = rand(0,$numberOfPost);
            $postToComment = Post::inRandomOrder()->limit($numberOfPostToComment)->get();
            foreach ($postToComment as $post) {
                $post->comments()->attach($user->id,['content' =>  fake()->realText($maxNbChars = 99, $indexSize = 2)]);
            }

        });


    }
}
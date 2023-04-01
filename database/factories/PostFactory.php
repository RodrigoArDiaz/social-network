<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

        return [
            'content' => fake()->realText($maxNbChars = 200, $indexSize = 2),
            // 'image' =>  $faker->imageUrl($width = 640, $height = 480),
            //Aleatoriamente se crea post con o sin fotos
            'image' => ((rand(1,100) % 2) == 0 ) ?  $faker->imageUrl($width = 640, $height = 480) : null,
            // 'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
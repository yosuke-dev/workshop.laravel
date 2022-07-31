<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $randomUser = User::inRandomOrder()->first();
    return [
        'title' => $faker->realText(20),
        'content_text' => $faker->realText(50),
        'user_id' => $randomUser->id
    ];
});

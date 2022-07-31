<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $randomPost = Post::inRandomOrder()->first();
    $randomUser = User::inRandomOrder()->first();
    return [
        'message' => $faker->realText(100),
        'post_id' => $randomPost->id,
        'user_id' => $randomUser->id
    ];
});

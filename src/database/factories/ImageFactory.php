<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use App\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    $randModel = $faker->boolean;
    return [
        'url' => $faker->url,
        'imageable_id' => $randModel ? User::inRandomOrder()->first()->id : Post::inRandomOrder()->first()->id,
        'imageable_type' => $randModel ? 'App\User' : 'App\Post',
    ];
});

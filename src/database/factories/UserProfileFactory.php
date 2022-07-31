<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\UserProfile;
use Faker\Generator as Faker;

$factory->define(UserProfile::class, function (Faker $faker) {
    $randomUser = User::inRandomOrder()->first();
    return [
        'country' => $faker->country(),
        'city' => $faker->city(),
        'user_id' => $randomUser->id
    ];
});

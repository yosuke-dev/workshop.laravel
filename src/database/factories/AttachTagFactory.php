<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AttachTag;
use App\Post;
use App\Tag;
use Faker\Generator as Faker;

$factory->define(AttachTag::class, function (Faker $faker) {
    $postIds = Post::all('id')->pluck('id');
    $tagIds = Tag::all('id')->pluck('id');
    $matrix = $postIds->crossJoin($tagIds);
    $keypair = $faker->unique()->randomElement($matrix);
    return [
        'post_id' => $keypair[0],
        'tag_id' => $keypair[1],
    ];
});

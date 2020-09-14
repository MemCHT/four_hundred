<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    $user_count = User::count();
    
    return [
        'user_id' => $faker->numberBetween(1,$user_count),
        'title' => $faker->text(255)
    ];
});

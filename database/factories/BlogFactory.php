<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Blog;
use App\Models\User;
use App\Models\Status;
use Faker\Generator as Faker;

$factory->define(Blog::class, function (Faker $faker) {
    $user_count = User::count();
    $status_count = Status::count()-1;
    
    return [
        'user_id' => $faker->numberBetween(1,$user_count),
        'title' => $faker->text(255),
        'status' => $faker->numberBetween(1,$status_count)
    ];
});

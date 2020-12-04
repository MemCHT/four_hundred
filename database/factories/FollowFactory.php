<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Follow;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Follow::class, function (Faker $faker) {
    $users_count = User::count();

    return [
        'from_user_id' => $faker->unique(true)->numberBetween(1, $users_count),
        'to_user_id' => $faker->unique(true)->numberBetween(1, $users_count)
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $article_count = Article::count();
    $user_count = User::count();
    
    return [
        'article_id' => $faker->numberBetween(1,$article_count),
        'user_id' => $faker->numberBetween(1,$user_count),
        'body' => $faker->paragraph()
    ];
});

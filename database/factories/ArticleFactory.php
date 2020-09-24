<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use App\Models\Blog;
use App\Models\Status;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Article::class, function (Faker $faker) {
    $blog_count = Blog::count();
    $status_count = Status::count();
    
    return [
        'blog_id' => $faker->numberBetween(1,$blog_count),
        'title' => $faker->text(40),
        'body' => $faker->text(400),
        'status_id' => $faker->numberBetween(1,$status_count)
    ];
});

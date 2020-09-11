<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Article;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Article::create([
            'blog_id' => 1,
            'title' => Str::random(40),
            'body' => Str::random(400)
        ]);
    }
}

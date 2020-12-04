<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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
            'title' => 'First Article Title',
            'body' => 'First Article Body aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'published_at' => new Carbon()
        ]);

        factory(Article::class,50)->create([
            'blog_id' => 1
        ]);

        factory(Article::class,1000)->create();
    }
}

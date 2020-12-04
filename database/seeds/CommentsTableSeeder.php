<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Comment::create([
            'article_id' => 1,
            'user_id' => 1,
            'status_id' => 1,
            'body' => 'First Comment Text'
        ]);

        factory(Comment::class,1600)->create();
    }
}

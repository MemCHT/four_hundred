<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Blog;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        /*Blog::create([
            'user_id' => 1,
            'title' => 'First Blog Title',
        ]);*/

        //factory(Blog::class,100)->create();
    }
}

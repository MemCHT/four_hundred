<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\Favorite;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Favorite::create([
            'article_id' => 1,
            'user_id' => 1
        ]);

        factory(Favorite::class,1000)->create();
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Blog;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'icon' => 'default.png'
        ]);
        Blog::create([
            'user_id' => 1,
            'title' => 'First Blog Title',
        ]);

        for($i=0;$i<100;$i++){
            $user = factory(User::class)->create();
            factory(Blog::class)->create(['user_id' => $user->id]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;

use App\Models\Follow;
use App\Models\User;

class FollowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_count = User::count();

        factory(Follow::class, $users_count-10)->create();
    }
}

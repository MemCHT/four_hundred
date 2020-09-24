<?php

use Illuminate\Database\Seeder;

use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $statuses = [
            ['公開','text-success'],
            ['非公開','text-danger'],
            ['下書き','text-warning']
        ];

        foreach($statuses as $status){
            Status::create([
                'name' => $status[0],
                'color' => $status[1]
            ]);
        }
    }
}

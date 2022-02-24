<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Manchester City', 'Liverpool', 'Chelsea', 'Manchester United'];

        foreach($names as $name){
            Team::create([
                'name' => $name,
                'strength' => rand(10,90)
            ]);
        }

    }
}

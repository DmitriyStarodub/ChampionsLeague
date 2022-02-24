<?php

namespace Tests\Feature;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaguesController extends TestCase
{
    /**
     * test Next Week
     *
     * @return void
     */
    public function testNextWeek()
    {
        $count_team = 10;

        $teams = Team::factory($count_team)->create();

        $response = $this->get(route('next_week'));

        $response->assertStatus(200);
    }

    /**
     * test all week
     *
     * @return void
     */
    public function testAllWeek()
    {
        $count_team = 10;

        $teams = Team::factory($count_team)->create();

        $response = $this->get(route('all'));

        $response->assertStatus(200);
    }
}

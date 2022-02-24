<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Team;

class TeamTest extends TestCase
{
    /**
     * Test probabilit that team has 100% chance to win
     *
     * @return void
     */
    public function testProbabilityHendred()
    {
        $count_team = 10;
        $teams = Team::factory($count_team)->create();
        $test_team = $teams->pop();
        $max_against_team = $teams->pop();

        $played = (($count_team - 1) * 2) - 2;
        $test_team->point = $played * Team::WON_POINT;
        $max_against_team->point = $test_team->point - (2 * Team::WON_POINT) - 1;

        $this->assertTrue($test_team->probability == 100);
    }

    /**
     * Test probabilit that team no chance to win
     *
     * @return void
     */
    public function testProbabilityNoChance()
    {
        $count_team = 10;
        $teams = Team::factory($count_team)->create();
        $test_team = $teams->pop();
        $max_against_team = $teams->pop();

        $played = (($count_team - 1) * 2) - 2;
        $test_team->setAttribute('played', $played);
        $max_against_team->point = $played * Team::WON_POINT;
        $test_team->point = $max_against_team->point - (2 * Team::WON_POINT) - 1;

        $this->assertTrue($test_team->probability == 0);
    }

    /**
     * Test probabilit that team has 100% chance to win
     *
     * @return void
     */
    public function testProbabilityEqualChance()
    {
        $count_team = 10;
        $teams = Team::factory($count_team)->create();
        $test_team = $teams->pop();
        $this->assertTrue($test_team->probability == (100 / $count_team));
    }
}

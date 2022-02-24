<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Models\Team;
use App\Models\Match;
use App\Services\MatchService;

class MatchServiceTest extends TestCase
{
    /**
     * Test random match status
     *
     * @return void
     */
    public function testGetMatchStatus()
    {
        $match_service = resolve(MatchService::class);

        $this->assertIsInt($match_service->getMatchStatus(10,50));

    }

    /**
     * Test current random match goals from match status
     *
     * @return void
     */
    public function testGetMatchGoalsFromLost()
    {
        $match_service = resolve(MatchService::class);

        $team_one_goals = 0;
        $team_two_goals = 0;
        $result = -1;

        $match_service->getMatchGoals($team_one_goals, $team_two_goals, $result);

        $this->assertTrue($team_two_goals > $team_one_goals);
    }

    /**
     * Test current random match goals from match status
     *
     * @return void
     */
    public function testGetMatchGoalsFromDrawn()
    {
        $match_service = resolve(MatchService::class);

        $team_one_goals = 0;
        $team_two_goals = 0;
        $result = 0;

        $match_service->getMatchGoals($team_one_goals, $team_two_goals, $result);

        $this->assertTrue($team_two_goals == $team_one_goals);
    }

    /**
     * Test current random match goals from match status
     *
     * @return void
     */
    public function testGetMatchGoalsFromWon()
    {
        $match_service = resolve(MatchService::class);

        $team_one_goals = 0;
        $team_two_goals = 0;
        $result = 1;

        $match_service->getMatchGoals($team_one_goals, $team_two_goals, $result);

        $this->assertTrue($team_two_goals < $team_one_goals);
    }
}

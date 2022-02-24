<?php

namespace App\Services;

use App\Models\Match;
use App\Models\Score;
use App\Models\Team;

class MatchService
{
    private $match;
    private $team;

    public function __construct(Match $match, Team $team)
    {
        $this->match = $match;
        $this->team = $team;
    }

    /*
    * Create match with scores for current stage
     *
     * @param Team $home_team
     * @param Team $guest_team
     * @param int $stage
    *
    * @return Match
    */
    public function createMatch($home_team, $guest_team, $stage): Match
    {
        $home_team_strength = $home_team->getCurrentStrength(true);
        $guest_team_strength = $guest_team->getCurrentStrength();

        $match_result = $this->getMatchStatus($home_team_strength,$guest_team_strength);
        $home_team_goals = 0;
        $guest_team_goals = 0;

        $this->getMatchGoals($home_team_goals, $guest_team_goals, $match_result);

        $match = $this->match->create([
            'home_team_id' => $home_team->id,
            'guest_team_id' => $guest_team->id,
            'stage' => $stage
        ]);

        Score::create([
            'match_id' => $match->id,
            'team_id' => $home_team->id,
            'goals' => $home_team_goals
        ]);

        Score::create([
            'match_id' => $match->id,
            'team_id' => $guest_team->id,
            'goals' => $guest_team_goals
        ]);

        return $match;
    }

    /*
    * Get status match 1 - won, 0 - drawn, -1 - lost
    *
    * @param int $team_strength_one
    * @param int $team_strength_two
    *
    * @return int
    */
    public function getMatchStatus(int $team_strength_one, int $team_strength_two)
    {
        $bufer = ($team_strength_one <=> $team_strength_two? $team_strength_one :$team_strength_two) / 3;

        $chance_team_one = rand(0, $team_strength_one);
        $chance_team_two = rand(0, $team_strength_two);

        if($chance_team_one > $chance_team_two + $bufer){
            $result = 1;
        }elseif($chance_team_one <= ($chance_team_two + $bufer) && $chance_team_two <= ($chance_team_one + $bufer)){
            $result = 0;
        }else{
            $result = -1;
        }

        return $result;
    }

    /*
    * Get random count goals from current match result status
    *
    * @param int &$team_one_goals
    * @param int &$team_two_goals
    * @param int $result
    */
    public function getMatchGoals(&$team_one_goals, &$team_two_goals, $result): void
    {
        if($result > 0){
            $team_one_goals = rand(1,5);
            $team_two_goals = rand(0, $team_one_goals);
        }elseif($result == 0){
            $team_one_goals = rand(0,5);
            $team_two_goals = $team_one_goals;
        }else{
            $team_two_goals = rand(1,5);
            $team_one_goals = rand(0, $team_two_goals);
        }
    }

    /*
   * Get random home team exept stage teams
   *
   * @param array $stage_team_ids
   * @return Team
   */
    public function getHomeTeam($stage_team_ids = [])
    {
        $home_teams = $this->team->whereNotIn('id', $stage_team_ids)->withCount('guest_mathes')->get()->shuffle();

        foreach($home_teams as $home_team)
        {
            if($home_team->count_guest_mathes < $this->team->count() - 1){
                return $home_team;
            }
        }
    }

    /*
    * Get random guest team exept stage teams
    *
    * @param array $stage_team_ids
    * @return Team
    */
    public function getGuestTeam($stage_team_ids = [])
    {
        $guest_teams = $this->team->whereNotIn('id', $stage_team_ids)->withCount('home_mathes')->get()->shuffle();
        foreach($guest_teams as $guest_team)
        {
            if($guest_team->count_guest_mathes < $this->team->count() - 1){
                return $guest_team;
            }
        }
    }

    /*
    * Get current number next stage
    *
    * @return int
    */
    public function getNextStage()
    {
        $stage = 1;
        if($this->match->max('stage') == (($this->team->count() - 1) * 2)){
            Match::query()->delete();
            Team::query()->update(['point' => 0]);
        }else{
            $stage = $this->match->max('stage') + 1;
        }

        return $stage;
    }
}

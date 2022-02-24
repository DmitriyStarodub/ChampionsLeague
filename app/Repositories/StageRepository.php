<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\Match;
use App\Services\MatchService;
use App\Services\TeamService;

class StageRepository
{
    private $match_service;
    private $team_service;

    public function __construct(MatchService $match_service, TeamService  $team_service)
    {
        $this->match_service = $match_service;
        $this->team_service = $team_service;
    }

    /*
     * Get result data for next week(stage)
     *
     * @return collection $matches
     */
    public function nextStage()
    {
        $next_stage = $this->match_service->getNextStage();

        $stage_team_ids = [];

        for($i=0; $i < (Team::count()/ 2); $i++){
            $home_team = $this->match_service->getHomeTeam($stage_team_ids);
            $stage_team_ids[] = $home_team->id;
            $guest_team = $this->match_service->getGuestTeam($stage_team_ids);
            $stage_team_ids[] = $guest_team->id;

            $match = $this->match_service->createMatch($home_team, $guest_team, $next_stage);
            $this->team_service->setPoint($home_team, $match);
            $this->team_service->setPoint($guest_team, $match);
        }

        $matches = Match::where('stage', $next_stage)->with(['home_team', 'guest_team', 'scores'])->get();

        return $matches;
    }

    /*
     * Get result data for all league(stages)
     *
     * @return collection $matches
     */
    public function allStages()
    {
        $count_stages = (Team::count() - 1) * 2;

        for($i = 0; $i < $count_stages; $i++){
            $this->nextStage();
        }

        return Match::with(['home_team', 'guest_team', 'scores'])->orderBy('stage')->get();
    }


}

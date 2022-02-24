<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StageRepository;
use App\Models\Team;

class LeaguesController extends Controller
{
    private $stage_repository;

    public function __construct(StageRepository $stage_repository)
    {
        $this->stage_repository = $stage_repository;
    }

    /*
     * Get result data for next week(stage)
     *
     * @return array('matches', 'teams')
     */
    public function nextWeek(Request $request)
    {
        $matches = $this->stage_repository->nextStage();

        $teams = Team::query()->orderByDesc('point')->get();

        return compact('matches', 'teams');
    }

    /*
     * Get result data for all league(stages)
     *
     * @return array('matches', 'teams')
     */
    public function playAll()
    {
        $matches = $this->stage_repository->allStages();

        $teams = Team::query()->orderByDesc('point')->get();

        return compact('matches', 'teams');
    }
}

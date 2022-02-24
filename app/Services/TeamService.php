<?php

namespace App\Services;

use App\Models\Match;
use App\Models\Team;

class TeamService
{
    /*
    * Set team point after match
    *
    */
    public function setPoint(Team $team, Match $match)
    {
        $team->point += $this->getPointFromStatus($match->statusInMatch($team->id));
        $team->save();
    }

    /*
    * Get amount point from match status
    */
    public function getPointFromStatus($status)
    {
        return $status < 0? Team::LOST_POINT: ($status == 0? Team::DRAWN_POINT: Team::WON_POINT);
    }
}

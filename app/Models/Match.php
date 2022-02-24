<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    protected $fillable = ['home_team_id', 'guest_team_id', 'stage'];

    public $timestamps = false;

    /*
     * Get team which play home belongs to match
     */
    public function home_team()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /*
    * Get team which play home belongs to match
    */
    public function guest_team()
    {
        return $this->belongsTo(Team::class, 'guest_team_id');
    }

    /*
    * Get scores for this match
    */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /*
     * Is the team lost this match
     *
     * @param int team_id
     * @return bool
     */
    public function isLostMatch($team_id): bool
    {
         return $this->scores->where('team_id', $team_id)->first()->goals < $this->scores->max('goals')? true: false;
    }

    /*
    * Is the team won this match
    *
    * @param int team_id
    * @return bool
    */
    public function isWonMatch($team_id): bool
    {
        return $this->scores()->where('team_id', $team_id)->first()->goals > $this->scores()->where('team_id', '!=', $team_id)->first()->goals? true: false;
    }

    /*
    * Is the team drawn this match
    *
    * @param int team_id
    * @return bool
    */
    public function isDrawnMatch($team_id): bool
    {
        return $this->scores->where('team_id', $team_id)->first()->goals == $this->scores->max('goals')? true: false;
    }

    /*
    * Get status team in match 1 - won, 0 - drawn, -1 - lost
    *
    * @param int team_id
    * @return int
    */
    public function statusInMatch($team_id): int
    {
        return $this->scores->where('team_id', $team_id)->first()->goals <=> $this->scores()->where('team_id', '!=', $team_id)->first()->goals;
    }
}

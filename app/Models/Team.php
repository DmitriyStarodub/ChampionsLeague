<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    const WON_POINT = 3;
    const DRAWN_POINT = 1;
    const LOST_POINT = 0;

    protected $fillable = ['name', 'strength', 'point'];

    protected $appends = [
        'played',
        'won',
        'drawn',
        'lost',
        'goals_difference',
        'probability',
    ];

    public $timestamps = false;

    /*
    * Get home matches for this team
    */
    public function home_mathes()
    {
        return $this->hasMany(Match::class, 'home_team_id');
    }

    /*
    * Get guest matches for this team
    */
    public function guest_mathes()
    {
        return $this->hasMany(Match::class, 'guest_team_id');
    }

    /*
    * Get scores for this team
    */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /*
    * Get count played this team
    */
    public function getPlayedAttribute()
    {
        return $this->scores()->count();
    }

    /*
    * Get count won this team
    *
    * @return int
    */
    public function getWonAttribute()
    {
        $mathes = $this->getMaches(['scores']);

        $count_won = 0;
        foreach($mathes as $mathe){
            $count_won += $mathe->isWonMatch($this->id)? 1 : 0;
        }

        return $count_won;
    }

    /*
    * Get count drawn this team
    *
    * @return int
    */
    public function getDrawnAttribute()
    {
        $mathes = $this->getMaches(['scores']);

        $count_drawn = 0;
        foreach($mathes as $mathe){
            $count_drawn += $mathe->isDrawnMatch($this->id)? 1 : 0;
        }

        return $count_drawn;
    }

    /*
    * Get count lost this team
    *
    * @return int
    */
    public function getLostAttribute()
    {
        $mathes = $this->getMaches(['scores']);

        $count_lost = 0;
        foreach($mathes as $mathe){
            $count_lost += $mathe->isLostMatch($this->id)? 1 : 0;
        }

        return $count_lost;
    }

    /*
    * Get goals difference for this team
    *
    * @return int
    */
    public function getGoalsDifferenceAttribute()
    {
        $mathe_ids = $this->home_mathes()->pluck('id');
        $mathe_ids = $mathe_ids->merge($this->guest_mathes()->pluck('id'));

        return $this->scores()->sum('goals') - Score::whereIn('match_id', $mathe_ids)->where('team_id', '!=', $this->id)->sum('goals');
    }

    /*
    * Get probability procent to win Champions League
    *
    * @return int
    */
    public function getProbabilityAttribute()
    {
        $max_against_point = self::where('id', '!=', $this->id)->max('point');
        $count_games = ((self::count() - 1) * 2) - $this->played;
        $count_teams = self::count();

        if($max_against_point + ($count_games * self::WON_POINT) < $this->point){
            $probability = 100;
        }elseif($this->point + ($count_games * self::WON_POINT) < $max_against_point){
            $probability = 0;
        }else{
            $probability = ($this->point + ($count_games * self::WON_POINT)) /
                (($count_games * self::WON_POINT * $count_teams) + $max_against_point);
            $probability = round($probability * 100, 2);
        }

        return $probability;
    }

    /*
    * Get current random teams strength
    *
    * @param bool $home_flag
    *
    * @return int
    */
    public function getCurrentStrength($home_flag = false)
    {
        $strength = !$this->strength? rand(10,90): $this->strength;

        if($home_flag){
            $strength = ($strength + $strength/10) > 95? 95: $strength + $strength/10;
        }

        return $strength;
    }

    /*
   * Get all teams matches
   *
   * @param array $relations
   *
   * @return collection
   */
    private function getMaches($relations = [])
    {
        $mathes = $this->home_mathes()->with($relations)->get();
        $mathes = $mathes->merge($this->guest_mathes()->with($relations)->get());

        return $mathes;
    }
}

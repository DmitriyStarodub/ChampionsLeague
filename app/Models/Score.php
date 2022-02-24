<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'team_id', 'goals'];

    public $timestamps = false;

    /*
     * Get the match that owns the score
     */
    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    /*
    * Get the team that owns the score
    */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}

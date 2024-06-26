<?php

namespace App\Models;

use App\Enums\ScoreType;
use App\Models\User;

use App\Models\Fixture;
use App\Services\ScoreService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $guarded = [];

    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUserPredictions($query)
    {
        return $query->whereUserId(Auth::user()->id);
    }

    public function scopeForCurrentUser($query)
    {
        return $query->whereUserId(Auth::user()->id);
    }

    public function hasNoScore()
    {
        return is_null($this->fixture->goals_home->count()) && is_null($this->fixture->goals_away->count());
    }

    public function decreaseScore(string $team): void
    {
        if ($this->{'score_' . $team} <= 0) {
            return;
        }

        $this->{'score_' . $team}--;
        $this->save();
    }

    public function increaseScore(string $team): void
    {
        if ($this->{'score_' . $team} >= 15) {
            return;
        }

        $this->{'score_' . $team}++;
        $this->save();
    }

    public function getPredictionStatus(): ?ScoreType
    {
        $scoreService = new ScoreService();
        return $scoreService->getPredictionStatus($this);
    }
}

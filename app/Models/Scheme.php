<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Scheme extends Model
{
    protected $appends = ['minPlayers'];

    public function getMinPlayersAttribute()
    {
        // Return the actual value you want to append
        return $this->minPlayers()->value('players'); // example
    }

    protected $fillable = [
        'id',
        'name',
        'set',
        'veiled',
        'unveiled'
    ];

    public function candidates()
    {
        return $this->morphMany(Candidate::class, 'entity');
    }

    public function decks()
    {
        return $this->morphMany(Deck::class, 'entity');
    }

    public function minPlayers(): HasOne
    {
        return $this->hasOne(MinPlayer::class);
    }
}

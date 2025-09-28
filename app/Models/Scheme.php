<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Scheme extends Model
{
    protected $appends = ['minPlayers'];

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

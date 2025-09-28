<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mastermind extends Model
{
    protected $fillable = [
        'id',
        'name',
        'set',
        'always_leads',

    ];

    public function candidates()
    {
        return $this->morphMany(Candidate::class, 'entity');
    }

    public function decks()
    {
        return $this->morphMany(Deck::class, 'entity');
    }
}

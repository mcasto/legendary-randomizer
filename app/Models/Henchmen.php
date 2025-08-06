<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Henchmen extends Model
{
    protected $fillable = [
        'id',
        'name',
        'set'
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

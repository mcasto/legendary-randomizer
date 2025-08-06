<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Villain extends Model
{
    protected $with = ['villain_keywords.keyword'];

    public function candidates()
    {
        return $this->morphMany(Candidate::class, 'entity');
    }

    public function decks()
    {
        return $this->morphMany(Deck::class, 'entity');
    }

    public function villain_keywords(): HasMany
    {
        return $this->hasMany(VillainKeyword::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hero extends Model
{
    protected $with = ['hero_colors', 'hero_teams', 'hero_keywords.keyword'];

    public function candidates()
    {
        return $this->morphMany(Candidate::class, 'entity');
    }

    public function decks()
    {
        return $this->morphMany(Deck::class, 'entity');
    }

    public function hero_colors(): HasMany
    {
        return $this->hasMany(HeroColor::class);
    }

    public function hero_teams(): HasMany
    {
        return $this->hasMany(HeroTeam::class);
    }

    public function hero_keywords(): HasMany
    {
        return $this->hasMany(HeroKeyword::class);
    }
}

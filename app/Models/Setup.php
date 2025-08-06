<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setup extends Model
{
    protected $with = ['decks'];
    protected $guarded = [];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function decks(): HasMany
    {
        return $this->hasMany(Deck::class);
    }
}

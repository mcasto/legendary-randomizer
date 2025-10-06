<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setup extends Model
{
    use SoftDeletes;

    // protected $with = ['decks']; // Temporarily disabled to prevent memory issues
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

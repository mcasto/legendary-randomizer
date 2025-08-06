<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialEntity extends Model
{
    protected $fillable = ['name'];

    public function decks()
    {
        return $this->morphMany(Deck::class, 'entity');
    }
}

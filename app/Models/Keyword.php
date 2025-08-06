<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keyword extends Model
{
    public function hero_keywords(): HasMany
    {
        return $this->hasMany(HeroKeyword::class);
    }

    public function villain_keywords(): HasMany
    {
        return $this->hasMany(VillainKeyword::class);
    }
}

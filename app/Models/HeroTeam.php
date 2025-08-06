<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HeroTeam extends Model
{
    protected $appends = ['value', 'label', 'icon'];

    protected $with = ['team'];

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->team->value ?? null,
        );
    }

    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->team->label ?? null,
        );
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->team->icon ?? null,
        );
    }

    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class, 'hero_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}

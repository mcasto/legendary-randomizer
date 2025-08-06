<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HeroColor extends Model
{
    // 1. Tell Laravel to always append these virtual attributes
    protected $appends = ['value', 'label', 'icon'];

    // 2. Define accessors for the color attributes
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->color->value ?? null,
        );
    }

    protected function label(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->color->label ?? null,
        );
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->color->icon ?? null,
        );
    }

    // 3. Optional: Eager load color by default
    protected $with = ['color'];

    // Relationships
    public function hero(): BelongsTo
    {
        return $this->belongsTo(Hero::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}

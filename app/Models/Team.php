<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $fillable = [
        'id',
        'value',
        'label',
        'icon'
    ];

    public function hero_teams(): HasMany
    {
        return $this->hasMany(HeroTeam::class);
    }

    public function heroes(): BelongsToMany
    {
        return $this->belongsToMany(Hero::class, 'hero_teams');
    }

    public static function getTeamsWithAvailableHeroes($setup_id, $numHeroes, $excluded = [])
    {
        // Ensure 0 is always excluded even if not in $excluded array
        $excludedTeams = array_unique(array_merge([0], $excluded));

        return self::whereNotIn('id', $excludedTeams) // Exclude specified teams + unaffiliated
            ->with(['heroes' => function ($query) use ($setup_id) {
                $query->whereHas('candidates', function ($q) use ($setup_id) {
                    $q->where('setup_id', $setup_id)
                        ->where('entity_type', 'heroes');
                });
            }])
            ->inRandomOrder() // Randomize team order
            ->get()
            ->filter(function ($team) use ($numHeroes) {
                return count($team->heroes) >= $numHeroes;  // find teams with enough heroes to fill roster
            })
            ->map(function ($team) {
                return [
                    'team' => $team->only(['id', 'value', 'label', 'icon']),
                    'available_heroes' => $team->heroes->map->only(['id', 'name', 'set']),
                    'available_heroes_count' => count($team->heroes)
                ];
            });
    }
}

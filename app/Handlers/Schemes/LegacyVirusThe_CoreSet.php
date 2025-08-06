<?php

// Legacy Virus, The
// coreset

// Setup : 8 Twists. Wound stack holds 6 Wounds per player.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class LegacyVirusThe_CoreSet extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->wounds = 6 * $this->setup->players;
    }
}

/*
    setup:
        players
        twists
        schemes
        masterminds
        villains
        henchmen
        heroes
        bystanders
        wounds
        officers
        shards
*/

<?php

// Build an Underground MegaVault Prison
// villains

// Setup : 8 Twists. The Bindings stack holds 5 Bindings per player.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class BuildAnUndergroundMegaVaultPrison_Villains extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->wounds = 5 * $this->setup->players;
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

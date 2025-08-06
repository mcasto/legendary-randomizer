<?php

// Maximum Carnage
// venom

// Setup : 10 Twists. Wound Stack has 6 Wounds per player.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class MaximumCarnage_Venom extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 10;
        $this->setup->wounds = $this->setup->players * 6;
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

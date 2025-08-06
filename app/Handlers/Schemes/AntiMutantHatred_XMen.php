<?php

// Anti-Mutant Hatred
// xmen

// Setup : 11 Twists. 30 Wounds.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class AntiMutantHatred_XMen extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->wounds = 30;
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

<?php

// Hidden Heart of Darkness
// noir

// Setup : 8 Twists. Shuffle the Mastermind Tactics into the Villain Deck as Villains.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class HiddenHeartOfDarkness_Noir extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
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

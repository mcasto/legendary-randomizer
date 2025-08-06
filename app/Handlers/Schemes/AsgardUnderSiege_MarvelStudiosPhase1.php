<?php

// Asgard Under Siege
// marvelstudios

// Setup : 8 Twists. Add an extra Henchman group to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class AsgardUnderSiege_MarvelStudiosPhase1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->henchmen++;
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

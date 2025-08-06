<?php

// Destroy the Cities of Earth!
// marvelstudios

// Setup : 8 Twists. 12 Bystanders in the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DestroyTheCitiesOfEarth_MarvelStudiosPhase1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->bystanders = 12;
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

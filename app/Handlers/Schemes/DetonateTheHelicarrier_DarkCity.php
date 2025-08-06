<?php

// Detonate the Helicarrier
// darkcity

// Setup : 8 Twists. 6 Heroes in the Hero Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DetonateTheHelicarrier_DarkCity extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes = 6;
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

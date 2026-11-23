<?php

// Super Hero Civil War
// coreset

// Setup : For 2-3 players, use 8 Twists. For 4-5 players, use 5 Twists. If only 2 players, use only 4 Heroes in the Hero Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SuperHeroCivilWar_CoreSet extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {

        $this->setup->twists = $this->setup->players < 4 ? 8 : 5;
        if ($this->setup->players == 2) {
            $this->setup->heroes = 4;
        }
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

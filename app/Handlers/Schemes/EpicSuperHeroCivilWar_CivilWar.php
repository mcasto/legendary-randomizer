<?php

// Epic Super Hero Civil War
// civilwar

// Setup : 1 player: 4 Heroes in Hero Deck. 1-3 players: 9 Twists. 4-5 players: 6 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class EpicSuperHeroCivilWar_CivilWar extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        if ($this->setup->players == 1) {
            $this->setup->heroes = 4;
        }

        $this->setup->twists = $this->setup->players < 4 ? 9 : 6;
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

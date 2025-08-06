<?php

// Reveal Heroes' Secret Identities
// civilwar

// Setup : 6 Twists. 7 Heroes in Hero Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class RevealHeroesSecretIdentities_CivilWar extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;
        $this->setup->heroes = 7;
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

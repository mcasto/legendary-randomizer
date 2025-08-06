<?php

// Go Back in Time to Slay Heroes' Ancestors
// captainamerica

// Setup : 9 Twists. 8 Heroes in Hero deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class GoBackInTimeToSlayHeroesAncestors_CaptainAmerica75thAnniversary extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;
        $this->setup->heroes = 8;
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

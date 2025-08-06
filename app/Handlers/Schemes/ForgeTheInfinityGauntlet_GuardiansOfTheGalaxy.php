<?php

// Forge the Infinity Gauntlet
// gotg

// Setup : 8 Twists. Always include the Infinity Gems Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Villain;
use App\Services\EntityService;

class ForgeTheInfinityGauntlet_GuardiansOfTheGalaxy extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // get infinity gems
        $gems = $this->es->pullCandidate(entityType: 'villains', name: 'Infinity Gems', take: 1);

        // add to deck
        $this->es->addToDeck($gems);

        // add expectation
        $this->addExpectation($gems);
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

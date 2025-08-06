<?php

// Demon Bear Saga, The
// newmutants

// Setup : 8 Twists. Include Demons of Limbo as one of the Villain Groups. Put the Demon Bear Villain from that groups next to the Scheme.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Villain;
use App\Services\EntityService;

class DemonBearSagaThe_TheNewMutants extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // get demons of limbo
        $limbo = $this->es->pullCandidate(entityType: 'villains', name: 'Demons of Limbo', take: 1);

        // add to villains
        $this->es->addToDeck(candidate: $limbo);

        // add expectations
        $this->addExpectation(candidate: $limbo);

        // remove candidate
        $limbo->delete();
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

<?php

// ...Open Rifts to Future Timelines
// messiahcomplex

//

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class OpenRiftsToFutureTimelines_MessiahComplex extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = -1;
        $this->setup->villains++;

        // get additional villain
        $villain = $this->es->getCandidate(entityType: 'villains');

        // add to deck as special
        $this->es->addToDeck(entityType: 'villains', entityId: $villain['id'], special: true);

        // add expectation
        $this->addExpectation(entityType: 'villains', entityId: $villain['id']);
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

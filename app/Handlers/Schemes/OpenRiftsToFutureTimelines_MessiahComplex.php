<?php

// ...Open Rifts to Future Timelines
// messiahcomplex

//

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Handlers\Traits\VeiledUnveiledSchemeTrait;

class OpenRiftsToFutureTimelines_MessiahComplex extends BaseHandler
{
    use VeiledUnveiledSchemeTrait;

    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        // Handle scheme setup (no specific twists for unveiled schemes)
        $this->handleSchemeSetup(0);

        $this->setup->twists = -1;
        $this->setup->villains++;

        // get additional villain
        $villain = $this->es->getCandidate(entityType: 'villains');

        // add to deck as special
        $this->es->addToDeck(candidate: $villain, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(section: 'villains', candidate: $villain);

        // Handle veiled/unveiled scheme pairing
        $this->handleVeiledUnveiledPairing();
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

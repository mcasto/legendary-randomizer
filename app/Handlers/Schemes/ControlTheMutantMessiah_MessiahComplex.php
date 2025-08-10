<?php

// ...Control the Mutant Messiah
// messiahcomplex

//

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Handlers\Traits\VeiledUnveiledSchemeTrait;
use App\Services\EntityService;

class ControlTheMutantMessiah_MessiahComplex extends BaseHandler
{
    use VeiledUnveiledSchemeTrait;

    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        // Handle scheme setup (no specific twists for unveiled schemes)
        $this->handleSchemeSetup(0);

        $this->setup->heroes++;
        $this->setup->villains++;

        $candidate = $this->es->getCandidate(entityType: 'heroes');

        // add to deck->heroes
        $this->es->addToDeck(candidate: $candidate, special: true);

        // add to deck->villains
        $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

        // add expectations
        $this->addExpectation(candidate: $candidate);
        $this->addExpectation(section: 'villains', candidate: $candidate);

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

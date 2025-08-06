<?php

// ...Control the Mutant Messiah
// messiahcomplex

//

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Services\EntityService;

class ControlTheMutantMessiah_MessiahComplex extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->heroes++;
        $this->setup->villains++;
        $this->setup->schemes++;

        $candidate = $this->es->getCandidate(entityType: 'heroes');

        // add to deck->heroes
        $this->es->addToDeck(candidate: $candidate, special: true);

        // add to deck->villains
        $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

        // add expectations
        $this->addExpectation(candidate: $candidate);
        $this->addExpectation(section: 'villains', candidate: $candidate);
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

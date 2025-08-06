<?php

// Kang, Quantum Conqueror
// msaw

// Always Leads: Armada of Kang. Set aside the Villains from an extra Villain Group as “Timeline Variants.“

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class KangQuantumConqueror_MarvelStudiosAntManAndTheWasp extends BaseHandler
{
    /**
     * Handle Masterminds operations.
     */
    protected function handle(): void
    {
        $this->setup->villains++;

        // get an extra villain group
        $candidate = $this->es->getCandidate(entityType: 'villains');

        // add to deck
        $this->es->addToDeck($candidate, special: true);

        // add expectation
        $this->addExpectation($candidate);

        if ($this->setup->players == 1) {
            // ignore always leads
            return;
        }

        // get armada
        $armada = $this->es->pullCandidate(entityType: 'villains', name: 'Armada of Kang', take: 1);

        // add to deck
        $this->es->addToDeck($armada);

        // add expectation
        $this->addExpectation($armada);
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

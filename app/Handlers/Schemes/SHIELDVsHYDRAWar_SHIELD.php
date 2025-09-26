<?php

// S.H.I.E.L.D. vs. HYDRA War
// shield

// Setup : 7 Twists. Include either the "Hydra Elite" or "A.I.M., Hydra Offshoot" Villain Group, but not both.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Villain;

class SHIELDVsHYDRAWar_SHIELD extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;

        $hydras = Villain::where('name', 'Hydra Elite')
            ->orWhere('name', 'A.I.M., Hydra Offshoot')
            ->inRandomOrder()
            ->get();

        foreach ($hydras as $hydra) {
            // get candidate
            $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $hydra->id);

            // remove candidate
            $this->es->removeCandidate($candidate['id']);
        }

        $villain = $hydras->pop();

        $candidate = (object)['entity_type' => 'villains', 'entity_id' => $villain->id];

        // add to deck
        $this->es->addToDeck(
            $candidate,
        );

        // add expectation
        $this->addExpectation(
            $candidate,
        );;
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

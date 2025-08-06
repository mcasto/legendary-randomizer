<?php

// Kree-Skrull War, The
// gotg

// Setup : 8 Twists. Always include Kree Starforce and Skrull Villain Groups.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Villain;
use App\Services\EntityService;

class KreeSkrullWarThe_GuardiansOfTheGalaxy extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        if ($this->setup->villains == 1) {
            $this->setup->villains = 2;
        }

        // kree starforce
        $kree = Villain::where('name', 'Kree Starforce')->first();
        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $kree->id);

        // add to deck
        $this->es->addToDeck($candidate);

        // addexp
        $this->addExpectation($candidate);

        // remove candidate
        $this->es->removeCandidate($candidate->id);

        // skrulls
        $skrulls = Villain::where('name', 'Skrulls')->first();
        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $skrulls->id);

        // add to deck
        $this->es->addToDeck($candidate);

        // add expectation
        $this->addExpectation($candidate);

        // remove candidate
        $this->es->removeCandidate($candidate->id);
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

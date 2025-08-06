<?php

// Splice Humans with Spider DNA
// pttr

// Setup : 8 Twists. Include Sinister Six as one of the Villain Groups.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Villain;

class SpliceHumansWithSpiderDNA_PaintTheTownRed extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // get sinister six
        $six = Villain::where('name', 'Sinister Six')
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $six->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'villains', entityId: $six->id);

        // add expectation
        $this->addExpectation(entityType: 'villains', entityId: $six->id);
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

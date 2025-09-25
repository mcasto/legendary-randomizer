<?php

// Ritual Sacrifice to Summon Chthon
// midnightsons

// Setup : 6 Twists, plus 1 per player. Add Lilin as an extra Villain Group. If using Lilith: Use 1 Twist total (and still use an extra Villain Group) .

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Villain;

class RitualSacrificeToSummonChthon_MidnightSons extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6 + $this->setup->players;
        $this->setup->villains++;

        // get lilin villain
        $lilin = Villain::where('name', 'Lilin')
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $lilin->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck($candidate, special: true);

        // add expectation
        $this->addExpectation($candidate);
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

<?php

// Shoot Hulk into Space
// wwhulk

// Setup : 8 Twists. Take 14 cards from an extra Hero with "Hulk" in its Hero Name. Shuffle them into a "Hulk Deck."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;

class ShootHulkIntoSpace_WorldWarHulk extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes++;

        // get hulk hero
        $hulk = Hero::where('name', 'regexp', '\bHulk\b')
            ->inRandomOrder()
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $hulk->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(candidate: $candidate, special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate);
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

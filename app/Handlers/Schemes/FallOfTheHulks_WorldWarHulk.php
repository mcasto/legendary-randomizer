<?php

// Fall of the Hulks
// wwhulk

// Setup : 10 Twists. 6 Wounds per player in Wound Stack. Use exactly two Heroes with "Hulk" in their Hero Names.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Hero;
use App\Services\EntityService;

class FallOfTheHulks_WorldWarHulk extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 10;
        $this->setup->wounds = $this->setup->players * 6;

        // get hulks
        $all = $this->es->pullCandidate(entityType: 'heroes', name: '\bHulk\b', isRegex: true);

        $hulks = $all->take(2);

        foreach ($hulks as $hulk) {
            // add to deck
            $this->es->addToDeck(candidate: $hulk);

            // add expectation
            $this->addExpectation(candidate: $hulk);
        }

        // remove remainder of hulk candidates
        $all->each->delete();
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

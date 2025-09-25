<?php

// Midnight Massacre
// midnightsons

// Setup : 11 Twists. Add all 14 cards for any Blade Hero to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Services\EntityService;

class MidnightMassacre_MidnightSons extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->villains++;
        $this->setup->heroes++;

        // get blade hero
        $blade = Hero::where('name', 'regexp', '\bBlade\b')
            ->inRandomOrder()
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $blade->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to villains
        $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate, section: 'villains');

        // add to heroes
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

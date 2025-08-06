<?php

// Mutating Gamma Rays
// wwhulk

// Setup : 7 Twists. Take 14 cards from an extra Hero with "Hulk" in its Hero Name. Put them in a face-up "Mutation Pile."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Services\EntityService;

class MutatingGammaRays_WorldWarHulk extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;
        $this->setup->villains++;
        $this->setup->heroes++;

        // get a hulk
        $hulk = Hero::where('name', 'regexp', '\bHulk\b')
            ->inRandomOrder()
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $hulk->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to villains
        $this->es->addToDeck(entityType: 'heroes', entityId: $hulk->id, section: 'villains', special: true);

        // add to heores
        $this->es->addToDeck(entityType: 'heroes', entityId: $hulk->id, special: true);

        // add expectations
        $this->addExpectation(entityType: 'heroes', entityId: $hulk->id, section: 'villains');

        $this->addExpectation(entityType: 'heroes', entityId: $hulk->id);
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

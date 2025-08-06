<?php

// Turn the Soul of Adam Warlock
// intothecosmos

// Setup : 14 Twists (using 3 Wounds to represent extra Scheme Twists) . Put 14 Adam Warlock Hero cards in a face up stack, ordered from lowest-cost on top, to highest-cost on the bottom.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;

class TurnTheSoulOfAdamWarlock_IntoTheCosmos extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 14;
        $this->setup->villains++;
        $this->setup->heroes++;

        // get adam
        $adam = Hero::where('name', 'Adam Warlock')
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $adam->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to villains
        $this->es->addToDeck(entityType: 'heroes', entityId: $adam->id, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $adam->id, section: 'villains');

        // add to heroes
        $this->es->addToDeck(entityType: 'heroes', entityId: $adam->id, section: 'heroes', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $adam->id, section: 'heroes');
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

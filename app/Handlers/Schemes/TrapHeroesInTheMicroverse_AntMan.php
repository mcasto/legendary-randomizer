<?php

// Trap Heroes in the Microverse
// antman

// Setup : 11 Twists. Add all 14 cards for and extra Hero the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class TrapHeroesInTheMicroverse_AntMan extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->villains++;
        $this->setup->heroes++;

        // get hero candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes');

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to villains
        $this->es->addToDeck(entityType: 'heroes', entityId: $candidate['entity_id'], section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $candidate['entity_id'], section: 'villains');

        // add to heroes
        $this->es->addToDeck(entityType: 'heroes', entityId: $candidate['entity_id'],  special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $candidate['entity_id']);
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

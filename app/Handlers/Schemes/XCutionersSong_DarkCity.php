<?php

// X-Cutioner's Song
// darkcity

// Setup : 8 Twists. Villain Deck includes 14 cards for an extra Hero and no Bystanders.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class XCutionersSong_DarkCity extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->villains++;
        $this->setup->heroes++;
        $this->setup->bystanders = 0;

        // get hero
        $hero = $this->es->getCandidate(entityType: 'heroes');

        // remove candidate
        $this->es->removeCandidate($hero['id']);

        // add to villains
        $this->es->addToDeck(entityType: 'heroes', entityId: $hero['entity_id'], section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $hero['entity_id'], section: 'villains');

        // add to heroes
        $this->es->addToDeck(entityType: 'heroes', entityId: $hero['entity_id'], section: 'heroes', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $hero['entity_id'], section: 'heroes');
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

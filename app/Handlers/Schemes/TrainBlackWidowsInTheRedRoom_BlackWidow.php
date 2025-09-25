<?php

// Train Black Widows in the Red Room
// blackwidow

// Setup : 8 Twists, minus 1 Twist per player. Add 8 S.H.I.E.L.D. Officers to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;

class TrainBlackWidowsInTheRedRoom_BlackWidow extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8 - $this->setup->players;

        // create special entity
        $special = SpecialEntity::create([
            'name' => '8 S.H.I.E.L.D. Officers'
        ]);

        $candidate = (object)['entity_type' => 'special_entities', 'entity_id' => $special->id];

        // add to deck
        $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate, section: 'villains');
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

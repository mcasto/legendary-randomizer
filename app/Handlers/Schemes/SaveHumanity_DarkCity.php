<?php

// Save Humanity
// darkcity

// Setup : 8 Twists. 24 Bystanders in the Hero Deck. (1 player: 12 Bystanders in the Hero Deck)

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;

class SaveHumanity_DarkCity extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes++;

        $numBystanders = $this->setup->players == 1 ? 12 : 24;

        // create special entity
        $special = SpecialEntity::create([
            'name' => "$numBystanders Bystanders"
        ]);

        $candidate = (object)['entity_type' => 'special_entities', 'entity_id' => $special->id];

        // add to deck
        $this->es->addToDeck(candidate: $candidate, section: 'heroes', special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate, section: 'heroes');
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

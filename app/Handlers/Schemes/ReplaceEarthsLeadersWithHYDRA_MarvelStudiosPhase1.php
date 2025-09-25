<?php

// Replace Earth's Leaders with HYDRA
// marvelstudios

// Setup : 5 Twists. 3 additional Twists next to this Scheme. 18 total Bystanders in the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;

class ReplaceEarthsLeadersWithHYDRA_MarvelStudiosPhase1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 5;
        $this->setup->bystanders = 18;

        // create special enttiy
        $special = SpecialEntity::create([
            'name' => '3 additional twists'
        ]);

        $candidate = (object) ['entity_type' => 'special_entities', 'entity_id' => $special->id];

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

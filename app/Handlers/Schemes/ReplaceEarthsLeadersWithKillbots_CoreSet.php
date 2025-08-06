<?php

// Replace Earth's Leaders with Killbots
// coreset

// Setup : 5 Twists. 3 additional Twists next to this Scheme. 18 total Bystanders in the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;

class ReplaceEarthsLeadersWithKillbots_CoreSet extends BaseHandler
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

        // add to deck
        $this->es->addToDeck(entityType: 'special_entities', entityId: $special->id, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'special_entities', entityId: $special->id, section: 'villains');
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

<?php

// Infiltrate the Lair with Spies
// villains

// Setup : 8 Twists, Stack 21 Bystanders next to this Plot as "Infiltrating Spies."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class InfiltrateTheLairWithSpies_Villains extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->villains++;

        // create special entity
        $special = SpecialEntity::create([
            'name' => '21 Bystanders'
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

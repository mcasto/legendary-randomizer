<?php

// Intergalactic Kree Nega-Bomb
// gotg

// Setup : 8 Twists. Make a face down 'Nega-Bomb Deck' of 6 Bystanders.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class IntergalacticKreeNegaBomb_GuardiansOfTheGalaxy extends BaseHandler
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
            'name' => '6 Bystanders'
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

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

        // create mock candidate for the special entity
        $candidate = (object)[
            'entity_type' => 'special_entities',
            'entity_id' => $special->id
        ];

        // add to deck
        $this->es->addToDeck($candidate, 'villains', true);

        // add expectation
        $this->addExpectation($candidate, 'villains');
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

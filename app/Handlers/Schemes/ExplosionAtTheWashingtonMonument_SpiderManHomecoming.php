<?php

// Explosion at the Washington Monument
// spiderhomecoming

// Setup : 8 Twists. Shuffle 18 Bystanders and 14 Wounds, then deal them evenly into eight decks. Put these decks in a row, as Floors of the Washington Monument.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class ExplosionAtTheWashingtonMonument_SpiderManHomecoming extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // create special entity
        $monument = SpecialEntity::create([
            'name' => '18 Bystanders and 14 Wounds'
        ]);

        $candidate = (object) [
            'entity_type' => 'special_entities',
            'entity_id' => $monument->id
        ];

        // add to deck
        $this->es->addToDeck(candidate: $candidate, special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate);
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

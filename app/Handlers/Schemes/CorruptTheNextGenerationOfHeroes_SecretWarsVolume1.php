<?php

// Corrupt the Next Generation of Heroes
// sw1

// Setup : 8 Twists. Add 10 to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class CorruptTheNextGenerationOfHeroes_SecretWarsVolume1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // create special entity
        $special = SpecialEntity::create([
            'name' => '10 Sidekicks'
        ]);


        $candidate = [
            'entity_type' => 'special_entities',
            'entity_id' => $special->id
        ];

        // add to deck
        $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(section: 'villains', candidate: $candidate);
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

<?php

// Sinister Ambitions
// sw2

// Setup : 6 Twists. Add 10 random Ambition cards to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;

class SinisterAmbitions_SecretWarsVolume2 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;
        $this->setup->villains++;

        // create special
        $ambitions = SpecialEntity::create([
            'name' => '10 random Ambitions'
        ]);

        // add to deck
        $this->es->addToDeck(entityType: 'special_entities', entityId: $ambitions->id, section: 'villains', special: true);

        // add expectations
        $this->addExpectation(entityType: 'special_entities', entityId: $ambitions->id, section: 'villains');
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

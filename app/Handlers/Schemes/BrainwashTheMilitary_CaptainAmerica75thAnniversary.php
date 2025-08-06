<?php

// Brainwash the Military
// captainamerica

// Setup : 7 Twists. Add 12 S.H.I.E.L.D. Officers to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Deck;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class BrainwashTheMilitary_CaptainAmerica75thAnniversary extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;



        // create special entity
        $entity = SpecialEntity::create([
            'name' => '12 S.H.I.E.L.D. Officers'
        ]);

        $candidate = ['entity_type' => 'special_entities', 'entity_id' => $entity->id];

        // add to deck
        $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

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

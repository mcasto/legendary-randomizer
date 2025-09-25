<?php

// Put Humanity on Trial
// annihilation

// Setup : 11 Twists. Stack 11 Bystanders next to the Scheme face down as "Galactic Jurors."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;

class PutHumanityOnTrial_Annihilation extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;

        // create special entity
        $special = SpecialEntity::create([
            'name' => '11 Bystanders as "Galactic Jurors"'
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

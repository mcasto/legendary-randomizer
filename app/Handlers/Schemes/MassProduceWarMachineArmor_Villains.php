<?php

// Mass Produce War Machine Armor
// villains

// Setup : 8 Twists, Include 10 S.H.I.E.L.D. Assault Squads as one of the Backup Adversary groups.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class MassProduceWarMachineArmor_Villains extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // create special entity
        $special = SpecialEntity::create([
            'name' => '10 S.H.I.E.L.D. Assault Squads'
        ]);

        $candidate = (object) ['entity_type' => 'special_entities', 'entity_id' => $special->id];

        // add to deck
        $this->es->addToDeck(candidate: $candidate, section: 'henchmen');

        // add expectation
        $this->addExpectation(candidate: $candidate, section: 'henchmen');
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

<?php

// Cage Villains in Power-Suppressing Cells
// villains

// Setup : 8 Twists. Stack 2 Cops per player next to this Plot.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class CageVillainsInPowerSuppressingCells_Villains extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // create new special entity for cops
        $cops = SpecialEntity::create([
            'name' => 'Cops'
        ]);

        $candidate = [
            'entity_type' => 'special_entities',
            'entity_id' => $cops->id
        ];

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

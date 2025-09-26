<?php

// Graduation at Xavier's X-Academy
// villains

// Setup : 8 Twists. Stack 8 Bystanders next to this Plot as "Young Mutants."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class GraduationAtXaviersXAcademy_Villains extends BaseHandler
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
            'name' => '8 Bystanders as "Young Mutants"'
        ]);

        $candidate = (object)[
            'entity_type' => 'special_entities',
            'entity_id' => $special->id
        ];

        // add to deck
        $this->es->addToDeck($candidate, section: 'villains', special: true);

        // add expectation
        $this->addExpectation($candidate, section: 'villains');
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

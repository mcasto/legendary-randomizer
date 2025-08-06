<?php

// Cytoplasm Spike Invasion
// wwhulk

// Setup : 10 Twists. Shuffle together 20 Bystanders and 10 Cytoplasm Spike Henchmen as an "Infected Deck."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Henchmen;
use App\Models\SpecialEntity;
use App\Services\EntityService;

class CytoplasmSpikeInvasion_WorldWarHulk extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 10;
        $this->setup->villains++;

        // find cytoplasm spikes
        $spikes = $this->es->pullCandidate(entityType: 'henchmen', name: 'Cytoplasm Spikes', take: 1);

        // remove candidate
        $spikes->delete();

        // create special entity
        $entity = SpecialEntity::create([
            'name' => '20 Bystanders + 10 Cytoplasm Spikes'
        ]);

        $candidate = [
            'entity_type' => 'special_entities',
            'entity_id' => $entity->id
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

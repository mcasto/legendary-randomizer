<?php

// Enslave Minds with the Chitauri Scepter
// marvelstudios

// Setup : 8 Twists. 6 Heroes. Chitauri Villain Group required. Shuffle 12 random Heroes from the Hero Deck into the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Villain;
use App\Services\EntityService;

class EnslaveMindsWithTheChitauriScepter_MarvelStudiosPhase1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes = 6;

        // get chitauri
        $chitauri = $this->es->pullCandidate(entityType: 'villains', name: 'Chitauri', take: 1);

        // add to deck
        $this->es->addToDeck(candidate: $chitauri);

        // add expectation
        $this->addExpectation(candidate: $chitauri);

        // remove candidate
        $chitauri->delete();
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

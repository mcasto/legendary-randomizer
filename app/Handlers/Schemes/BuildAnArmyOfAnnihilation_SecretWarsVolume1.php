<?php

// Build an Army of Annihilation
// sw1

// Setup : 9 Twists. Put 10 extra Annihilation Wave Henchmen in the KO pile.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Services\EntityService;

class BuildAnArmyOfAnnihilation_SecretWarsVolume1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;
        $this->setup->henchmen++;

        // select a henchmen from candidates
        $henchmen = $this->es->pullCandidate('henchmen');

        // add to deck
        $this->es->addToDeck(candidate: $henchmen, special: true);

        // add expectation
        $this->addExpectation(candidate: $henchmen);

        // remove from candidates
        $henchmen->delete();
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

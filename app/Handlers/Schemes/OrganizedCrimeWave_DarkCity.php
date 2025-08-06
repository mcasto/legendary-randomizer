<?php

// Organized Crime Wave
// darkcity

// Setup : 8 Twists. Include 10 Maggia Goons as one of the Henchman Groups.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Henchmen;

class OrganizedCrimeWave_DarkCity extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // get maggia goons
        $goons = Henchmen::where('name', 'Maggia Goons')
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'henchmen', entityId: $goons->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'henchmen', entityId: $goons->id);

        // add expectation
        $this->addExpectation(entityType: 'henchmen', entityId: $goons->id);
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

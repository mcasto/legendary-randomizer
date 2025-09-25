<?php

// Scavenge Alien Weaponry
// spiderhomecoming

// Setup : 7 Twists. Add an extra Henchmen Group of 10 cards as "Smugglers."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class ScavengeAlienWeaponry_SpiderManHomecoming extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;
        $this->setup->henchmen++;

        // get henchmen
        $henchmen = $this->es->getCandidate(entityType: 'henchmen');

        // remove candidate
        $this->es->removeCandidate($henchmen['id']);

        // add to deck
        $this->es->addToDeck(candidate: $henchmen, special: true);

        // add expectation
        $this->addExpectation(candidate: $henchmen);
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

<?php

// Invade the Daily Bugle News HQ
// pttr

// Setup : 8 Twists. Add 6 extra Henchmen from a single Henchman Group to the Hero Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Services\EntityService;

class InvadeTheDailyBugleNewsHQ_PaintTheTownRed extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes++;
        $this->setup->henchmen++;

        // get a henchmen candidate
        $candidate = $this->es->getCandidate('henchmen');

        // add to deck
        $this->es->addToDeck(candidate: $candidate, section: 'heroes', special: true);
        $this->es->addToDeck(candidate: $candidate, section: 'henchmen', special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate, section: 'heroes');
        $this->addExpectation(candidate: $candidate, section: 'henchmen');

        // remove candidate
        $this->es->removeCandidate($candidate['id']);
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

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

        // get a henchmen candidate
        $candidate = $this->es->getCandidate('henchmen');

        // add to deck
        $this->es->addToDeck(entityType: 'henchmen', entityId: $candidate['entity_id'], section: 'heroes', special: true);

        // add expectation
        $this->addExpectation(entityType: 'henchmen', entityId: $candidate['entity_id'], section: 'heroes');
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

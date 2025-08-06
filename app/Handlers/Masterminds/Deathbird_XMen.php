<?php

// Deathbird
// xmen

// Always Leads: Shi'ar Imperial Guard and a Shi'ar Henchmen Group.

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class Deathbird_XMen extends BaseHandler
{
    /**
     * Handle Masterminds operations.
     */
    protected function handle(): void
    {
        if ($this->setup->players == 1) {
            // ignore always leads
            return;
        }

        // shi'ar guards
        $guards = $this->es->pullCandidate(entityType: 'villains', name: "Shi'ar Imperial Guard", take: 1);

        // henchmen
        $hench = $this->es->pullCandidate(entityType: 'henchmen', name: "\bShi'ar\b", take: 1, isRegex: true);

        // add always leads to deck
        $this->es->addToDeck($guards);

        // add expectation
        $this->addExpectation($guards);

        // add always leads to deck
        $this->es->addToDeck($hench);

        // add expectation
        $this->addExpectation($hench);

        // remove gaurds candidate
        $guards->delete();

        // remove hench candidate
        $hench->delete();
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

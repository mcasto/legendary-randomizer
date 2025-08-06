<?php

// Sinister Six 2099
// 2099

// Always Leads: Any “Alchemax“ or “Sinister“ Villain Group

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class SinisterSix2099_Marvel2099 extends BaseHandler
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

        $alwaysLeads = $this->es->pullCandidate(
            entityType: 'villains',
            name: "\b(?:Alchemax|Sinister)\b",
            isRegex: true,
            take: 1
        );

        // add always leads to deck
        $this->es->addToDeck($alwaysLeads);

        // add expectation
        $this->addExpectation($alwaysLeads);

        // remove candidate
        $alwaysLeads->delete();
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

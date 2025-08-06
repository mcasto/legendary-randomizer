<?php

// Macho Gomez
// deadpool

// Always Leads: Deadpool's “Friends“

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class MachoGomez_Deadpool extends BaseHandler
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

        // have to use regex because of funky quotes in villain name
        $alwaysLeads = $this->es->pullCandidate(entityType: 'villains', name: "\bDeadpool's\b\s.Friends.", take: 1, isRegex: true);

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

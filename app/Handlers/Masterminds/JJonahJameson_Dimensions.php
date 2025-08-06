<?php

// J. Jonah Jameson
// dimensions

// Always Leads: Spider-Slayers

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class JJonahJameson_Dimensions extends BaseHandler
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

        // for some reason, in the database, it's not Spider-Slayers, it's Spider-Slayer
        $alwaysLeads = $this->es->pullCandidate(entityType: 'henchmen', name: 'Spider-Slayer', take: 1);

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

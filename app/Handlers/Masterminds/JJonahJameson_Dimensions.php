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

        // get mastermind record
        $mastermind = Mastermind::find(61);

        logger()->info($mastermind);

        // find always leads candidate
        $alVillain = $this->es->pullCandidate(entityType: 'villains', name: $mastermind->always_leads, take: 1);

        logger()->info($alVillain);

        $alHench = $this->es->pullCandidate(entityType: 'henchmen', name: $mastermind->always_leads, take: 1);

        logger()->info($alHench);

        $alwaysLeads = $alVillain ?? $alHench;

        logger()->info($alwaysLeads);

        if (!($alVillain || $alHench)) {
            dd("Always Leads not found for " . $mastermind->name);
        }

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

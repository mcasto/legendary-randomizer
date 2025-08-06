<?php

// Ultron Infinity
// mswi

// Always Leads: Ultron Sentries

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class UltronInfinity_MarvelStudiosWhatIf extends BaseHandler
{
    /**
     * Handle Masterminds operations.
     */
    protected function handle(): void
    {
        // get mastermind record
        $mastermind = Mastermind::find(96);

        // find always leads candidate
        $alVillain = $this->es->pullCandidate(entityType: 'villains', name: $mastermind->always_leads, take: 1);

        $alHench = $this->es->pullCandidate(entityType: 'henchmen', name: $mastermind->always_leads, take: 1);

        $alwaysLeads = $alVillain ?? $alHench;

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

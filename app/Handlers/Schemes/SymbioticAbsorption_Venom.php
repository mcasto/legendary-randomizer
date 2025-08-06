<?php

// Symbiotic Absorption
// venom

// Setup : 11 Twists. Set aside a second "Drained" Mastermind and its 4 Tactics, out of play. Add its "Always Leads" Villains as an extra Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\SpecialEntity;

class SymbioticAbsorption_Venom extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->masterminds++;
        $this->setup->villains++;

        // get special mastermind candidate (need full candidate to get always leads)
        $response = $this->mastermindWithAlwaysLeads();

        // remove candidate
        $this->es->removeCandidate($response['candidate']->id);

        // add to deck
        $this->es->addToDeck(entityType: 'masterminds', entityId: $response['mastermind']->id, special: true);

        // add expectation
        $this->addExpectation(entityType: 'masterminds', entityId: $response['mastermind']->id);

        // increment villains or henchmen depending on always leads
        if ($response['entity_type'] == 'villains') {
            $this->setup->villains++;
        } else {
            $this->setup->henchmen++;
        }

        // add that to villain/henchmen deck
        $this->es->addToDeck(entityType: $response['entity_type'], entityId: $response['always_leads']->id);

        // get candidate
        $candidate = $this->es->getCandidate(entityType: $response['entity_type'], entityId: $response['always_leads']->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add expectation
        $this->addExpectation(entityType: $response['entity_type'], entityId: $response['always_leads']->id);
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

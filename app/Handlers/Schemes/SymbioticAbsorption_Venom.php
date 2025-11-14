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

        // get special mastermind candidate (need full candidate to get always leads)
        $response = $this->mastermindWithAlwaysLeads();

        // get mastermind candidate
        $mm = $response['candidate'];

        // get always_leads candidate
        $al = $this->es->getCandidate(entityType: $response['entity_type'], entityId: $response['always_leads']['id']);

        // remove mm candidate
        $this->es->removeCandidate($mm->id);

        // remove al candidate
        $this->es->removeCandidate($al->id);

        // add to deck
        $this->es->addToDeck(candidate: $mm, special: true);

        // add expectation
        $this->addExpectation(candidate: $mm);

        // increment villains or henchmen depending on always leads
        if ($response['entity_type'] == 'villains') {
            $this->setup->villains++;
        } else {
            $this->setup->henchmen++;
        }

        // add that to villain/henchmen deck
        $this->es->addToDeck(candidate: $al);

        // add expectation
        $this->addExpectation(candidate: $al);
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

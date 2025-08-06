<?php

// House of M
// revelations

// Setup : 8 Twists. Hero Deck is 4 Heroes and 2 non- Heroes. (Or substitute another team for all icons on both sides.) Add 14 Scarlet Witch Hero cards to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Hero;
use App\Models\HeroTeam;
use App\Models\Team;
use App\Services\EntityService;
use App\Services\TeamService;
use Illuminate\Support\Facades\DB;

class HouseOfM_Revelations extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes = 7;
        $this->setup->villains++;

        // mc-todo: figure out why getTeamsWithAvailableHeroes triggers infinite loop

        // get a team with at least 4 heroes
        $four_heroes = Team::getTeamsWithAvailableHeroes(setup_id: $this->setup->id, numHeroes: 4)
            ->take(1)
            ->first();

        // get a team with at least 2 heroes (that's not the previously picked team)
        $two_heroes = Team::getTeamsWithAvailableHeroes(setup_id: $this->setup->id, numHeroes: 2, excluded: [$four_heroes['team']['id']])
            ->take(1)
            ->first();

        // pull heroes from teams
        $heroes = array_merge(
            $four_heroes['available_heroes']
                ->take(4),
            $two_heroes['available_heroes']
                ->take(2)
        );

        dd($heroes);

        // foreach ($heroes as $hero) {
        //     $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $hero['id']);

        //     // add to deck
        //     $this->es->addToDeck(entityType: 'heroes', entityId: $hero['id']);

        //     // add expectation
        //     $this->addExpectation(entityType: 'heroes', entityId: $hero['id']);

        //     // remove candidate
        //     $this->es->removeCandidate($candidate['id']);
        // }

        // // get scarlet witch
        // $witch = Hero::where('name', 'Scarlet Witch')->first();

        // // get candidate
        // $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $witch->id);

        // // remove candidate
        // $this->es->removeCandidate($candidate['id']);

        // // add to villin deck
        // $this->es->addToDeck(entityType: 'heroes', entityId: $witch->id, section: 'villains', special: true);

        // // add expectation
        // $this->addExpectation(entityType: 'heroes', entityId: $witch->id, section: 'villains');

        // // add to hero deck
        // $this->es->addToDeck(entityType: 'heroes', entityId: $witch->id, special: true);

        // // add expectation
        // $this->addExpectation(entityType: 'heroes', entityId: $witch->id);
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

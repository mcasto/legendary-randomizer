<?php

// Duels of Science and Magic
// doctorstrange

// Setup : 2 players: 9 Twists. 1 or 4 players: 10 Twists. 3 or 5 players: 11 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DuelsOfScienceAndMagic_DoctorStrangeAndTheShadowsOfNightmare extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        switch ($this->setup->players) {
            case 2:
                $this->setup->twists = 9;
                break;

            case 1:
            case 4:
                $this->setup->twists = 10;
                break;

            default:
                $this->setup->twists = 11;
        }
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

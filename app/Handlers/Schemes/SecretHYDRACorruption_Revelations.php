<?php

// Secret HYDRA Corruption
// revelations

// Setup : 30 Officers in the S.H.I.E.L.D. Officer stack. 1 player: 7 Twists. 2-3 players: 9 Twists. 4-5 players: 11 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SecretHYDRACorruption_Revelations extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        switch ($this->setup->players) {
            case 1:
                $this->setup->twists = 7;
                break;

            case 2:
            case 3:
                $this->setup->twists = 9;
                break;

            default:
                $this->setup->twists = 11;
        }

        $this->setup->officers = 30;
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

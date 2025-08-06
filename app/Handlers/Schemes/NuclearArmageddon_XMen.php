<?php

// Nuclear Armageddon
// xmen

// Setup : 5 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class NuclearArmageddon_XMen extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=5;
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

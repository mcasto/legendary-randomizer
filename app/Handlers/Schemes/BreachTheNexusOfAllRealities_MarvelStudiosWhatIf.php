<?php

// Breach the Nexus of All Realities
// mswi

// Setup : (1-2 players: Use 3 Villain Groups.) Stack each Villain Group seperately face down as its own "Reality." Add 2 Twists to each Reality. Shuffle together all the Henchmen, Master Strikes, and Bystanders for your player count and randomly distribute them amongst all the Realities, as evenly as possible. Shuffle each Reality seperately.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class BreachTheNexusOfAllRealities_MarvelStudiosWhatIf extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        if ($this->setup->players < 3) {
            $this->setup->villains = 3;
        }

        $this->setup->twists = 2 * $this->setup->villains;
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

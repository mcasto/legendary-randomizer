<?php

namespace App\Services;

use App\Models\Expect;
use App\Models\Setup;

class CheckExpectations
{
    /**
     * Get teams with minimum amount of heroes
     */
    public static function validate(Setup $setup)
    {
        // check that inDeck matches setup expectations
        foreach (['schemes', 'masterminds', 'villains', 'henchmen', 'heroes'] as $key) {
            $items = $setup->decks
                ->filter(fn($item) => $item->section == $key);

            if (count($items) != $setup->{$key}) {
                logger()->error([
                    'key' => $key,
                    'setup_count' => $setup->{$key},
                    'deck_count' => count($items)
                ]);

                return false;
            }
        }

        // get expectations
        $expectations = Expect::where('setup_id', $setup['id'])
            ->get();

        foreach ($expectations as $expectation) {
            $items = $setup->decks
                ->filter(fn($item) => $item->section == $expectation->section)
                ->filter(fn($item) => $item->entity_type == $expectation->entity_type && $item->entity_id == $expectation->entity_id);

            if (count($items) == 0) {
                return false;
            }
        }

        return true;
    }
}

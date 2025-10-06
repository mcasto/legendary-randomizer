<?php

namespace App\Services;

use App\Models\Setup;
use App\Models\Hero;
use App\Models\Villain;
use App\Models\Mastermind;
use App\Models\Scheme;
use App\Models\Henchmen;

class OutputDeckService
{
    public static function build(Setup $setup): bool|array
    {
        $meetsExpectations = CheckExpectations::validate($setup);

        $output = [
            'expected' => $meetsExpectations,
            'setup' => $setup->toArray(),
            'deck' => []
        ];

        // Load decks with their polymorphic entities and nested relationships
        $decks = $setup->decks()->with([
            'entity' => function ($morphTo) {
                $morphTo->morphWith([
                    Hero::class => ['hero_colors.color', 'hero_teams.team', 'hero_keywords.keyword'],
                    Villain::class => ['villain_keywords.keyword'],
                    Mastermind::class => [],
                    Scheme::class => [],
                    Henchmen::class => [],
                ]);
            }
        ])->get();

        foreach ($decks as $deck) {
            $section = $deck->section;
            $entity = $deck->entity;
            $entity->special = $deck->special;

            $output['deck'][$section][] = $entity;
        }

        // alphabetize the entities in each section of the deck
        $sections = ['schemes', 'masterminds', 'villains', 'henchmen', 'heroes'];
        foreach ($sections as $section) {
            if (isset($output['deck'][$section])) {
                $output['deck'][$section] = collect($output['deck'][$section])
                    ->sortBy('name')
                    ->values()
                    ->all();
            }
        }

        return $output;
    }
}

<?php

namespace App\Services;

use App\Models\Setup;

class OutputDeckService
{
    public static function build(Setup $setup): bool|array
    {
        $meetsExpectations = CheckExpectations::validate($setup);
        if (!$meetsExpectations) {
            throw new \RuntimeException('Setup does not meet expectations');
        }

        $output = [
            'setup' => $setup->toArray(),
            'deck' => []
        ];

        $decks = $setup->decks;

        foreach ($decks as $deck) {
            $section = $deck->section;
            $entity = $deck->entity;
            $entity->special = $deck->special;

            $output['deck'][$section][] = $entity;
        }

        return $output;
    }
}

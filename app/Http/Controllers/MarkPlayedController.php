<?php

namespace App\Http\Controllers;

use App\Models\PlayedCount;
use Illuminate\Http\Request;

class MarkPlayedController extends Controller
{
    public function update(Request $request)
    {
        $data_id = $request->user()->data_id;

        $decks = collect($request->gameId['decks']);

        $entities = $decks->map(function ($deck) {
            return [
                'entity_type' => $deck['entity_type'],
                'entity_id' => $deck['entity_id']
            ];
        })->filter(function ($entity) {
            return $entity['entity_id'] !== null;
        });

        $entities->each(function ($entity) use ($data_id) {
            PlayedCount::create([
                'data_id' => $data_id,
                'entity_type' => $entity['entity_type'],
                'entity_id' => $entity['entity_id']
            ]);
        });
    }
}

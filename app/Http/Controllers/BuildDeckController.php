<?php

namespace App\Http\Controllers;

use App\Services\EntityService;
use App\Services\OutputDeckService;
use Illuminate\Http\Request;

class BuildDeckController extends Controller
{
    public function show(Request $request, int $numPlayers)
    {
        $schemeID = $request->input('scheme') ?? null;
        $mastermindID = $request->input('mastermind') ?? null;

        // get user
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        // initial scaffolding of setup & candidates
        $es = new EntityService(
            numPlayers: $numPlayers,
            dataID: $user->data_id
        );

        // get random scheme
        $es->getScheme($schemeID);

        // get random mastermind
        $es->getMastermind($mastermindID);

        // fill with random villains
        $es->fillEntities('villains');

        // fill with random henchmen
        $es->fillEntities('henchmen');

        // fill with random heroes
        $es->fillEntities('heroes');

        $es->runHandlerLog();

        try {
            $result = OutputDeckService::build($es->setup);

            return response()->json([
                'status' => 'success',
                'game' => [
                    'setup' => $result['setup'],
                    'deck' => $result['deck']
                ],
                'expected' => $result['expected']
            ]);
        } catch (\RuntimeException $e) {
            logger()->error($e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

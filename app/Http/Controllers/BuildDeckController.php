<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\HandlerLog;
use App\Models\Setup;
use App\Services\EntityService;
use App\Services\OutputDeckService;
use Illuminate\Http\Request;

class BuildDeckController extends Controller
{
    public function show(Request $request, int $numPlayers)
    {
        // delete all setups for this data_id. Setups are soft-deleted, so the
        // FK cascade never fires; clear the build-time scratch rows explicitly.
        $oldSetupIds = Setup::where('data_id', $request->user()->data_id)->pluck('id');
        if ($oldSetupIds->isNotEmpty()) {
            Candidate::whereIn('setup_id', $oldSetupIds)->delete();
            HandlerLog::whereIn('setup_id', $oldSetupIds)->delete();
            Setup::whereIn('id', $oldSetupIds)->delete();
        }

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
                    'deck' => $result['deck'],
                    'created' => $es->setup->created_at
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

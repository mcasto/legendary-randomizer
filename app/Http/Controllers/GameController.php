<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Services\EntityService;
use App\Services\OutputDeckService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function show(Request $request)
    {
        // get most recent setup
        $setup = Setup::where('data_id', $request->user()->data_id)
            ->orderBy('created_at', 'desc')
            ->first();

        try {
            $result = OutputDeckService::build($setup);

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

    public function destroy(int $id, Request $request)
    {
        //
    }
}

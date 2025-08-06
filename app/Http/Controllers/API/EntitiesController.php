<?php

namespace App\Http\Controllers\API;

use App\Models\HasSet;
use App\Models\Hero;
use App\Models\Mastermind;
use App\Models\Scheme;
use App\Models\Villain;
use App\Models\Henchmen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EntitiesController extends ResController
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }

        // Get user's sets
        $userSets = HasSet::where('data_id', $user->data_id)
            ->pluck('set_value')
            ->toArray();

        // If user has no sets, return empty arrays
        if (empty($userSets)) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'heroes' => [],
                    'masterminds' => [],
                    'schemes' => [],
                    'villains' => [],
                    'henchmen' => [],
                ]
            ]);
        }

        try {
            // Get Masterminds
            $masterminds = Mastermind::whereIn('set', $userSets)
                ->get(['id', 'name', 'set']);

            // Get Schemes
            $schemes = Scheme::whereIn('set', $userSets)
                ->orderBy('name')
                ->get(['id', 'name', 'set']);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'masterminds' => $masterminds,
                    'schemes' => $schemes,
                ]
            ]);
        } catch (\Exception $e) {
            logger()->error('Error fetching entities: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch entities'
            ], 500);
        }
    }
}

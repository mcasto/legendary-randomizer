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
use Illuminate\Support\Facades\DB;

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
            // Get Heroes
            $heroes = Hero::whereIn('set', $userSets)
                ->get(['id', 'name', 'set']);

            // Get Villains
            $villains = Villain::whereIn('set', $userSets)
                ->get(['id', 'name', 'set']);

            // Get Henchmen
            $henchmen = Henchmen::whereIn('set', $userSets)
                ->get(['id', 'name', 'set']);

            // Get Masterminds
            $masterminds = Mastermind::whereIn('set', $userSets)
                ->get(['id', 'name', 'set']);

            // Get Schemes (one record per unique name)
            $schemes = Scheme::whereIn('set', $userSets)
                ->whereIn('id', function ($query) use ($userSets) {
                    $query->select(DB::raw('MIN(id)'))
                        ->from('schemes')
                        ->whereIn('set', $userSets)
                        ->groupBy('name');
                })
                ->orderBy('name')
                ->get(['id', 'name', 'set']);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'heroes' => $heroes,
                    'villains' => $villains,
                    'henchmen' => $henchmen,
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

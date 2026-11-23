<?php

namespace App\Http\Controllers;

use App\Models\MinPlayer;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function schemesIndex()
    {
        $schemes = Scheme::orderBy('name')
            ->get();

        return $schemes;
    }

    public function updateScheme(int $id, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'minPlayers' => 'required|integer',
        ]);

        if ($validated->fails()) {
            return ['status' => 'error', 'message' => 'Minimum players must be a whole number'];
        }

        $minPlayers = MinPlayer::where('scheme_id', $id)
            ->first();

        if (!$minPlayers) {
            return ['status' => 'error', 'message' => 'No minimum players record for this scheme'];
        }

        $minPlayers->players = $validated->valid()['minPlayers'];
        $minPlayers->save();

        return ['status' => 'success'];
    }
}

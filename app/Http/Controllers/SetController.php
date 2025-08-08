<?php

namespace App\Http\Controllers;

use App\Models\HasSet;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => Set::orderBy('label')
            ->get()]);
    }

    public function addSet($set_value, Request $request)
    {
        $hasSet = HasSet::where('set_value', $set_value)
            ->where('data_id', $request->user()->data_id)
            ->first();

        if (!$hasSet) {
            HasSet::create([
                'data_id' => $request->user()->data_id,
                'set_value' => $set_value
            ]);
        }
    }

    public function removeSet($set_value, Request $request)
    {
        $hasSet = HasSet::where('set_value', $set_value)
            ->where('data_id', $request->user()->data_id)
            ->first();

        if ($hasSet) {
            $hasSet->delete();
        }
    }
}

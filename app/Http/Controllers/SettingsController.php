<?php

namespace App\Http\Controllers;

use App\Models\HenchmenDisplay;
use App\Models\HeroDisplay;
use App\Models\MastermindDisplay;
use App\Models\SchemeDisplay;
use App\Models\UserSettings;
use App\Models\VillainDisplay;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(Request $request): void
    {
        $models = [
            'heroes' => HeroDisplay::class,
            'masterminds' => MastermindDisplay::class,
            'villains' => VillainDisplay::class,
            'henchmen' => HenchmenDisplay::class,
            'schemes' => SchemeDisplay::class
        ];

        $displays = $request->input('displays', []);

        foreach ($displays as $key => $rec) {
            $model = $models[$key];
            $dbRec = $model::where('user_data_id', $request->user()->data_id)
                ->first();

            $dbRec->bg = $rec['bg'];
            $dbRec->text = $rec['text'];
            $dbRec->order = $rec['order'];
            $dbRec->save();
        }

        $settings = UserSettings::where(
            'user_data_id',
            $request->user()->data_id
        )
            ->first();

        $settings->use_epics = $request->input(
            'use_epics',
            $settings->use_epics
        );

        $settings->use_played_count = $request->input(
            'use_played_count',
            $settings->use_played_count
        );

        $settings->save();
    }
}

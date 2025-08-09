<?php

namespace App\Http\Controllers;

use App\Models\UserSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(Request $request): void
    {
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

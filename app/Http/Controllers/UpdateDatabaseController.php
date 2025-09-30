<?php

namespace App\Http\Controllers;

use App\Models\Henchmen;
use App\Models\Hero;
use App\Models\HeroColor;
use App\Models\HeroTeam;
use App\Models\Keyword;
use App\Models\Mastermind;
use App\Models\MinPlayer;
use App\Models\Scheme;
use App\Models\Set;
use App\Models\Team;
use App\Models\Villain;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UpdateDatabaseController extends Controller
{
    private $modelMap = [
        'sets' => Set::class,
        'keywords' => Keyword::class,
        'teams' => Team::class,
        'masterminds' => Mastermind::class,
        'villains' => Villain::class,
        'henchmens' => Henchmen::class,
        'heroes' => Hero::class,
        'schemes' => Scheme::class
    ];

    private function compileKeywordDetails($array)
    {
        $html = '';
        if (is_string($array)) return $array;


        foreach ($array as $key => $value) {
            if ($key == 'icon') {
                $html .= '<img src="/images/icon/' . $value . '.svg"  width="30px" height="15px" style="object-fit: cover;">';
                continue;
            }

            if (is_array($value)) {
                if ($key === 'points') {
                    $html .= '<ul>';
                    foreach ($value as $subArray) {
                        $html .= '<li>' . $this->compileKeywordDetails($subArray) . '</li>';
                    }
                    $html .= '</ul>';
                } else {
                    $html .= $this->compileKeywordDetails($value);
                }
            } else {
                if ($key === 'bold') {
                    $html .= '<strong>' . $value . '</strong>';
                } else {
                    $html .= $value;
                }
            }
        }
        return $html;
    }

    private function sanitize_class_name($input)
    {
        // Remove any characters that aren't allowed in class names (including Unicode punctuation)
        $sanitized = preg_replace('/[^\p{L}\p{N}\s_\x7f-\xff]/u', '', $input);

        // Ensure the first character is valid (letter or underscore)
        if (!preg_match('/^[\p{L}_\x7f-\xff]/u', $sanitized)) {
            // If not, prepend an underscore
            $sanitized = '_' . $sanitized;
        }

        // Convert to StudlyCase
        $sanitized = Str::studly($sanitized);

        return $sanitized;
    }

    public function update(Request $request)
    {
        file_put_contents(__DIR__ . '/request.json', json_encode($request->all(), JSON_PRETTY_PRINT));

        $handlers = ['masterminds' => [], 'schemes' => []];

        foreach ($request->all() as $table => $recs) {
            $model = $this->modelMap[$table];
            $model::query()->delete();
            foreach ($recs as $rec) {
                if ($rec['id'] > -1) {
                    $model::create($rec);

                    if ($table == 'schemes') {
                        $minPlayers = MinPlayer::where('scheme_id', $rec['id'])
                            ->first();
                        if (!$minPlayers) {
                            MinPlayer::create([
                                'scheme_id' => $rec['id'],
                                'players' => 1
                            ]);
                        }
                    }

                    if ($table == 'schemes' || $table == 'masterminds') {
                        $set = Set::where('value', $rec['set'])
                            ->first();
                        $handlerName = $this->sanitize_class_name($rec['name']) . "_" . $this->sanitize_class_name($set->label);

                        $filename = dirname(__DIR__, 2) . '/Handlers/' . Str::studly($table) . '/' . $handlerName . '.php';

                        $handlerExists = file_exists($filename);

                        if (!$handlerExists) {
                            $handlers[$table][] = $rec['name'];
                        }
                    }

                    if ($table == 'heroes') {
                        foreach ($rec['colors'] as $color) {
                            HeroColor::create([
                                'hero_id' => $rec['id'],
                                'color_id' => $color
                            ]);
                        }

                        foreach ($rec['teams'] as $team) {
                            HeroTeam::create([
                                'hero_id' => $rec['id'],
                                'team_id' => $team
                            ]);
                        }
                    }
                }
            }
        }

        return ['message' => 'Database updated', 'handlers' => $handlers];
    }
}

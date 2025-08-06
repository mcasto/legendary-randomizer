<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Keyword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    private $keys = [];

    private function parseDetails($keyword): string
    {
        $icons = collect([
            [
                'id' => 1,
                'label' => 'ATTACK'
            ],
            [
                'id' => 2,
                'label' => 'RECRUIT'
            ],
            [
                'id' => 3,
                'label' => 'COST'
            ],
            [
                'id' => 4,
                'label' => 'VP'
            ],
            [
                'id' => 5,
                'label' => 'FOCUS'
            ],
            [
                'id' => 6,
                'label' => 'PIERCING '
            ],
            [
                'id' => 7,
                'label' => 'TOKEN'
            ],
        ]);

        $text = '';

        foreach ($keyword['details'] as $details) {
            if (is_array($details)) {
                foreach ($details as $key => $detailItem) {
                    if ($key == 'header') {
                        $text = "<h1 class='keyword-header'>$detailItem</h1>";
                    }
                    if (is_numeric($key)) {
                        if (is_array($detailItem)) {
                            $key = key($detailItem);
                            $item = $detailItem[$key];
                            switch ($key) {
                                case "bold":
                                    $text .= "<strong>$item</strong>";
                                    break;

                                case "italic":
                                    $text .= "<em>$item</em>";
                                    break;

                                case "header":
                                    $text .= "<h1 class='keyword-header'>$item</h1>";
                                    break;

                                case "rule":
                                    $text .= "Shards";
                                    break;

                                case "keyword":
                                    $text .= "<strong>$item</strong>";
                                    break;

                                case "hc":
                                    $color = Color::find($item)->icon;
                                    $text .= "<div>$color</div>";
                                    break;

                                case "icon":
                                    $icon = $icons->first(fn($icon) => $icon['id'] == $item);

                                    $text .= $icon['label'];

                                    break;
                            }

                            $this->keys[] = key($detailItem);
                            // print_r(['key' => $key, 'detailItem' => $detailItem]);
                        } else {
                            $text .= $detailItem;
                        }
                    }
                }
            } else {
                return "<p>$details</p>";
            }
        }

        return $text;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('keywords');
        foreach ($recs as $keyword) {
            $rec = [
                'id' => $keyword['id'],
                'value' => $keyword['value'],
                'label' => $keyword['label'],
                'details' => $this->parseDetails($keyword)
            ];

            Keyword::create($rec);
        }
    }
}

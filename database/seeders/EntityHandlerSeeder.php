<?php

namespace Database\Seeders;

use App\Models\EntityHandler;
use App\Models\Set;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EntityHandlerSeeder extends Seeder
{
    /**
     * Mastermind Parsers
     */
    private function parseAbility($ability)
    {
        if (is_array($ability)) {
            foreach ($ability as $a) {
                if (isset($a['bold'])) {
                    if ($a['bold'] == 'Always Leads') {
                        return $ability[1];
                    }
                }
            }
        }

        return false;
    }

    private function parseAbilities($card)
    {
        $abilities = $card['abilities'];
        foreach ($abilities as $ability) {
            $response = $this->parseAbility($ability);
            if ($response) {
                return preg_replace("/^:\s/", "", trim($response));
            }
        }
    }

    private function parseCards($cards)
    {
        $response = false;
        foreach ($cards as $card) {
            $response = $this->parseAbilities($card);
            if ($response) {
                return $response;
            }
        }
    }
    // end Mastermind Parsers

    /**
     * Scheme Parsers
     */
    private function abilitiesToText(array $abilities): string
    {
        $textTokens = [];

        foreach ($abilities as $ability) {
            if (is_array($ability)) {
                // Handle nested ability arrays
                $textTokens[] = $this->abilitiesToText($ability);
            } elseif (is_string($ability)) {
                $textTokens[] = trim($ability);
            } elseif (is_array($ability)) {
                // Handle object-style formatting
                if (isset($ability['bold'])) {
                    $textTokens[] = $ability['bold'];
                } elseif (isset($ability['italic'])) {
                    $textTokens[] = $ability['italic'];
                } elseif (isset($ability['text'])) {
                    $textTokens[] = $ability['text'];
                }
            }
        }

        return implode(' ', array_filter($textTokens));
    }

    private function extractSetupText(array $scheme): ?string
    {
        if (!isset($scheme['cards'])) {
            return '';
        }

        foreach ($scheme['cards'] as $card) {
            if (!isset($card['abilities']) || !is_array($card['abilities'])) {
                continue;
            }

            foreach ($card['abilities'] as $abilityGroup) {
                if (!is_array($abilityGroup) || empty($abilityGroup)) {
                    continue;
                }

                // Check if this is a Setup ability group
                $firstElement = $abilityGroup[0] ?? null;
                if (is_array($firstElement) && isset($firstElement['bold']) && $firstElement['bold'] === 'Setup') {
                    return $this->abilitiesToText($abilityGroup);
                } elseif (is_string($firstElement) && strpos($firstElement, 'Setup:') === 0) {
                    return $firstElement;
                }
            }
        }

        return '';
    }
    // end Scheme Parsers

    /**
     * Get Setup from indicated type
     */
    private function getSetup($type, $entity)
    {
        if ($type == 'masterminds') {
            return $this->parseCards($entity['cards']);
        }

        if ($type == 'schemes') {
            return $this->extractSetupText($entity);
        }

        // special case here ... there's only one villain with a handler, and rather than parsing its card elements, I just typed the setup text here.
        if ($type == 'villains') {
            return "Choose an unused Henchman Group and stack Henchmen from it next to this Scheme equal to the number of players.";
        }
    }

    /**
     * Sanitizes a string to be safe for use as a PHP class name.
     *
     * @param string $input The string to be sanitized
     * @return string The sanitized class name
     */
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

    /**
     * Build Class Name
     */
    private function buildClassName($name, $set)
    {
        $fullSet = Set::where('value', $set)
            ->first()
            ->toArray();

        return $this->sanitize_class_name($name) . "_" . $this->sanitize_class_name($fullSet['label']);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // truncate EntityHandler
        EntityHandler::truncate();

        // get raw seed data
        $data = json_decode(file_get_contents(__DIR__ . '/seed-data.json'), true);

        $types = ['schemes', 'masterminds'];

        $recs = [];

        // build recs for the types
        foreach ($types as $type) {
            $entities = $data[$type];

            foreach ($entities as $entity) {
                $ms = json_decode($entity['ms'], true);
                $id = $ms['id'];
                $name = $ms['name'];
                $set = $ms['set'];
                $className = $this->buildClassName($name, $set);

                $setup = $this->getSetup($type, $ms);

                $recs[] = [
                    'type' => $type,
                    'className' => $className,
                    'name' => $name,
                    'set' => $set,
                    'setup' => $setup,
                    'entity_type' => $type,
                    'entity_id' => $id,
                    'handler_class' => $className,
                    'filename' => "/Users/mikecasto/laravel-projects/legendary-randomizer/app/Handlers/" . Str::studly($type) . "/" . $className . ".php"
                ];
            }
        }

        // get the lone villain handler info
        $entity = array_filter($data['villains'], fn($villain) => json_decode($villain['ms'], true)['id'] == 121);

        $type = 'villains';
        $ms = json_decode(array_shift($entity)['ms'], true);
        $id = $ms['id'];
        $name = $ms['name'];
        $set = $ms['set'];
        $className = $this->buildClassName($name, $set);

        $setup = $this->getSetup($type, $ms);

        $recs[] = [
            'type' => $type,
            'className' => $className,
            'name' => $name,
            'set' => $set,
            'setup' => $setup,
            'entity_type' => $type,
            'entity_id' => $id,
            'handler_class' => $className,
            'filename' => "/Users/mikecasto/laravel-projects/legendary-randomizer/app/Handlers/Villains/" . $className . ".php"
        ];

        // make handler files / db recs
        foreach ($recs as $rec) {
            extract($rec);
            preg_match("/([0-9]+)\stwists/i", $rec['setup'], $m);
            $twists = $m[1] ?? -1;

            $name = str_replace("\"", "'", $name);
            $setup = str_replace("\"", "'", $setup);
            $cmd = "php artisan make:handler $className --type=$type --name=\"$name\" --set=$set --setup=\"$setup\" --id=$entity_id --twists=$twists";

            print "Making: $type/$className\n";

            shell_exec($cmd);
        }
    }
}

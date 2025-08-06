<?php

namespace App\Console\Commands;

use App\Models\Mastermind;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeTestMastermindsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test-masterminds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * Execute the console command.
     */
    public function handle()
    {
        $fs = new Filesystem();
        $masterminds = Mastermind::where('handler_done', 0)
            ->orderBy('name')
            ->get();

        foreach ($masterminds as $mastermind) {
            $testName =  $this->sanitize_class_name($mastermind->name) . "MastermindTest";

            $filePath = "/Users/mikecasto/laravel-projects/legendary-randomizer/tests/Feature/$testName.php";

            shell_exec("php artisan make:test $testName");

            $stub = file_get_contents(__DIR__ . '/stubs/mastermind-test.stub');
            $contents = str_replace(['{{ TestName }}', '{{ mastermind_id }}'], [$testName, $mastermind->id], $stub);

            file_put_contents($filePath, $contents);
        }
    }
}

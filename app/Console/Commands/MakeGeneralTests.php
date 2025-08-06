<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeGeneralTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:general-tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for ($i = 0; $i < 100; $i++) {
            $testName = "General_" . sprintf("%05s", $i) . "_Test";

            $filePath = "/Users/mikecasto/laravel-projects/legendary-randomizer/tests/Feature/$testName.php";

            shell_exec("php artisan make:test $testName");

            $stub = file_get_contents(__DIR__ . '/stubs/general-test.stub');
            $contents = str_replace(['{{ TestName }}'], [$testName], $stub);

            file_put_contents($filePath, $contents);
        }
    }
}

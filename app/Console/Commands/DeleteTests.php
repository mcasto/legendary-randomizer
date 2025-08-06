<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DeleteTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:tests';

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
        $fs = new Filesystem();

        $files = glob("/Users/mikecasto/laravel-projects/legendary-randomizer/tests/Feature/*.php");

        foreach ($files as $file) {
            $fs->delete($file);
        }
    }
}

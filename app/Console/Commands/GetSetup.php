<?php

namespace App\Console\Commands;

use App\Models\Setup;
use Illuminate\Console\Command;

class GetSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:get-setup
                            {id? : The id of the setup to retrieve}';

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
        $id = $this->argument->id ?? Setup::latest('id')->first()->id;

        $setup = Setup::find($id);
        $decks = $setup->decks;

        $output = [];

        foreach ($decks as $deck) {
            $key = $deck->entity_type;
            $entity = $deck->entity;

            if (!isset($output[$key])) {
                $output[$key] = [];
            }

            if ($entity) {
                $output[$key] = [
                    'id' => $entity->id,
                    'name' => $entity->name
                ];
            } else {
                $output[$key][] = [
                    'null_entity' => true,
                    'entity_type' => $deck->entity_type,
                    'entity_id' => $deck->entity_id
                ];
            }
        }

        logger()->debug($output);
    }
}

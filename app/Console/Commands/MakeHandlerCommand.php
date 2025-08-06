<?php

namespace App\Console\Commands;

use App\Models\EntityHandler;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeHandlerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:handler
                            {className : The name of the handler}
                            {--type= : The entity type for the handler}
                            {--name= : The name of the entity}
                            {--set= : The set the entity belongs to}
                            {--setup= : Note for entity [always leads or setup instructions}
                            {--id= : ID for entity in database}
                            {--twists= : Number of twists [only for schemes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new handler class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $className = $this->argument('className');
        $type = $this->option('type') ?? 'default';
        $name = $this->option('name');
        $set = $this->option('set');
        $setup = $this->option('setup');
        $id = $this->option('id');
        $twists = $this->option('twists');

        $type = ucfirst($type);

        $filesystem = new Filesystem();

        // Ensure directory exists
        $directory = app_path("Handlers/{$type}");
        if (!$filesystem->isDirectory($directory)) {
            $filesystem->makeDirectory($directory, 0755, true);
        }

        // Handler class path
        $path = "{$directory}/{$className}.php";

        // Create database record in entity_handlers
        $rec = [
            'entity_type' => $type,
            'entity_id' => $id,
            'handler_class' => "App\\Handlers\\{$type}\\" . $className
        ];

        EntityHandler::upsert([
            $rec,
        ], uniqueBy: [
            'entity_id',
            'entity_type'
        ], update: [
            'handler_class'
        ]);

        // Get stub content
        $stub = $this->getStub();

        // Replace placeholders
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ type }}', '{{ name }}', '{{ set }}', '{{ setup }}', '{{ twists }}', '{{ id }}'],
            ["App\\Handlers\\{$type}", $className, $type, $name, $set, $setup, $twists, $id],
            $stub
        );

        // if ($filesystem->exists($path)) {
        //     unlink($path);
        // }

        // Create file
        if (!$filesystem->exists($path)) {
            $filesystem->put($path, $content);
        }

        $this->info("Handler created successfully: {$path}");
    }

    /**
     * Get the stub file contents.
     */
    protected function getStub(): string
    {
        $filename = __DIR__ . "/stubs/" . $this->option('type') . "-handler.stub";
        return file_get_contents($filename);
    }
}
